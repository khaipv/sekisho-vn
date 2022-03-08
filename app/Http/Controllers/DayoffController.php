<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Tck_master;
use App\Tck_dayoff;
use App\Tck_user;
use App\Division;
use DB;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class DayoffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    const OT_CR_STATUS  =101; 
    const OT_CP_STATUS  =402; 
    const OT_CR_CODE  =401; 
    const OT_LEAVE_CODE  =405; 
    const OT_CR_ANNUAL=201;
    const OT_CR_CP=202;
    const OT_CR_SPECIAL=203;
        const OT_DELETE_CP=404;

     const MAN_DENY_1=103;
    const MAN_DENY_2=104;
    public function index()
    {
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
      $master=Tck_master::all();
      $masterTerm=$master;
       $masterFrom=$master;
        $masterTo=$master;
      $userH = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.id','=','users.id')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();
      $t = Carbon::now();
         $days = DB::table('tck_dayyear as dy')
          ->leftjoin('tck_print as pr', 'pr.date', '=',
           DB::raw('dy.date and pr.code='.$userH->code))
          ->whereRaw("year(dy.date)=".  $t->year)
          ->whereRaw("month(dy.date)=".  $t->month)
           ->whereRaw("day(dy.date)=".  $t->day)
          ->selectRaw('dy.*,pr.attendance,pr.leaving')
         ->first();
      $rest='0';
       // Tính ra thời gian nghỉ rest nếu attendance,leaving nằm giữa rest time thì set rest=1:00
      if ($days->attendance< $userH->restE && $days->leaving< $userH->restE) {
         $rest=$userH->rest;
      }
      // select union day
     $dayAlls =  DB::table('tck_overtime as ot')
       ->leftjoin('tck_master as typetbl', 'ot.statusCP','=','typetbl.val')
       ->leftjoin('tck_master as statustbl', 'ot.status','=','statustbl.val')
       ->leftjoin('tck_master as termtbl', 'ot.term','=','termtbl.val')
       ->where('ot.usrID','=',$userH->code)
       ->whereRaw("year(ot.date)=".  $t->year)
       ->whereRaw("month(ot.date)=".  $t->month)
       ->where('statusCP','=','402')
       ->where('ot.status','<>','404')
      ->select(['ot.oriNum as oriNum','ot.datenum as datenum','ot.date as date','termtbl.name as term','typetbl.name as mtype','statustbl.name as mstatus','note',DB::raw('null as toDate'),DB::raw('null as mtermFrom '),DB::raw('null as mtermTo')]) ->orderBy('date','ASC') ->get();
        return view('dayoff.index', compact('master','userH','days','rest','masterTerm','masterFrom','masterTo','dayAlls'));
    }

        public function masterSearch(Request $request)
    {
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
           $masterName=$request->input('nameScr');
           $masterType=$request->input('masterTypeScr');
           $master = Master::sortable()
            ->leftjoin('master_type', 'master_type.code', '=', 'master.type')
            ->orderBy('type', 'desc')
            ->select('master.*','master_type.name as typeName')
             ->where(function ($query)use ($masterName) {
                
                 $query->Where ('master.name','like','%' . $masterName . '%' );
                    })
             ->where(function ($query)use ($masterType) {
                
                 $query->Where ('master.type','like','%' . $masterType . '%' );
                    })
            ->paginate(10);
              $masterType = DB::table('master_type') ->get();
               $masterTypeS=$masterType;
       
         return view('master.index', compact('master','masterType','masterTypeS'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create()
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
        return view('client.createclient');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function store(Request $request)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
          $toDate = Carbon::parse($request->input('toDate'));
        $fromDate= Carbon::parse($request->input('fromDate'));
        $days = $toDate->diffInDays($fromDate);
           $auUser=Tck_user::findOrFail($user->id);
        $dayoff= new Tck_dayoff
     ([
         'usrCode'=> $auUser->code,
         'fromDate' => $request->get('fromDate'),
         'fromTime'=>$request->get('fromTime'),
         'toDate' => $request->get('toDate'),
         'toTime'=>$request->get('toTime'),
         'type'=>$request->get('type'),
         'note'=>$request->get('note'),
         'usrUDP'=>$user->id,
         'status'=>1,
         'days'=>$days,
                ]);
        $dayoff->save();
          return back();
    }

    /**
     * Display the specified resource.
     *Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
     
      dd($request->input('code'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
       $client = client::find($id);
           $division = DB::table('division')
          ->leftjoin('staff', 'division.pic', '=', 'staff.id')
          ->leftjoin('users', 'division.pic_s', '=', 'users.id')
          ->select('division.*',  'staff.name as name2', 'staff.department as department', 'staff.email as email2','staff.tell as tell2','staff.status as status2','users.name as pics')
          ->where("division.companyid","=", $id)
          ->paginate(10);
            return view('master.editmaster', compact('client','id','division'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
            $auUser=Tck_user::findOrFail($user->id);

       $dayoffUdp = Tck_dayoff::findOrFail($id);
       if (!is_null($dayoffUdp)) {
         if ($dayoffUdp->usrCode==$auUser->code) {
           $dayoffUdp->fromDate =  $request->get('fromDate');
           $dayoffUdp->fromTime =  $request->get('fromTime');
           $dayoffUdp->toDate =  $request->get('toDate');
           $dayoffUdp->toTime =  $request->get('toTime');
            $dayoffUdp->type =  $request->get('type');
             $dayoffUdp->note =  $request->get('note');
           $dayoffUdp->usrUDP =  $user->id;
           $dayoffUdp->save();
           $master = Tck_master::sortable()
            ->orderBy('id', 'desc')
            ->sortable()
            ->paginate(10);
             $userH = DB::table('tck_user')
                  ->leftjoin('users', 'tck_user.id','=','users.id')
                   ->select('users.*')
                   ->first();
         }
       }
      // $dayoffUdp->update($request->all());
        
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
        $dayoffUdp = Tck_dayoff::findOrFail($id);
        $dayoffUdp->delete();

        return back();

    }
    public function mySearch(Request $request)
    {
        $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         $companyname=$request->input('companynamesrc');
        
        $client = DB::table('client')
        
            ->leftjoin('staff', 'client.pic', '=', 'staff.id')
            ->leftjoin('staff_sekisho', 'client.pic_s', '=', 'staff_sekisho.id')
            ->where(function ($query)use ($companyname) {
                $query->whereNull('client.companyname')
                      ->orWhere ('client.companyname','like','%' . $companyname . '%' );
            })
            ->select('client.*', 'staff_sekisho.name as name_s', 'staff.name as name2', 'staff.department as department', 'staff.email as email2','staff.tell as tell2','staff.status as status2')
            ->orderBy('id', 'asc')
            ->paginate(10);
        return view("client.index")->with('client',$client);
    }
    public function showClient($code)
    {
        $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
       $client = client::find($id);
           $division = DB::table('division')
          ->leftjoin('staff', 'division.pic', '=', 'staff.id')
           ->leftjoin('users', 'division.pic_s', '=', 'users.id')
           ->leftJoin('master as imp', function ( $join ) use  ($imp) {
            $join->on('division.rate','=','imp.code')
                 ->where('imp.type','=',$imp);
             })
           ->leftJoin('master as introduce', function ( $join ) use  ($p_Introduce) {
            $join->on('division.introduce','=','introduce.code')
                 ->where('introduce.type','=',$p_Introduce);
             })
              ->leftJoin('master as advertise', function ( $join ) use  ($p_advertise) {
            $join->on('division.advertise','=','advertise.code')
                 ->where('advertise.type','=',$p_advertise);
             })
          ->select('division.*',  'staff.name as name2', 'staff.department as department', 'staff.email as email2','staff.tell as tell2','staff.status as status2' ,'introduce.name as introduceName','imp.name as rate2','advertise.name as advertiseName','users.name as pics')
          ->where("division.code","=", $code)
          ->orderBy('id', 'desc')
          ->paginate(10);

           $count =$division->count();
            return view('client.detailclient', compact('client','id','division','count'));
    }
}