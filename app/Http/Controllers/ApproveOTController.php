<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Tck_master;
use App\Tck_dayoff;
use App\Tck_user;
use App\Division;
use App\Tck_dayyear;
use App\Tck_annualleave;
use App\Tck_workinghours;
use DB;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class ApproveOTController extends Controller
{
    /**
     * Display a listing of the resource.
     *`
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
           $master = Tck_master::sortable()
            ->orderBy('id', 'desc')
            ->sortable()
            ->paginate(10);
             $userH = DB::table('tck_user')
                  ->leftjoin('users', 'tck_user.id','=','users.id')
                   ->select('users.*')
                   ->first();
           $dayoff = DB::table('tck_overtime')
                      ->leftjoin('tck_user', 'tck_user.code','=','tck_overtime.usrCode')
                      ->leftjoin('tck_master as status', 'status.id','=','tck_overtime.status')     
                      ->select('tck_overtime.*','status.name as statusName' 
                        ,'tck_user.name as usrName'
                      )
                      ->paginate(50);
         return view('approve.approve', compact('master','userH','dayoff'));
    }
        public function masterSearch(Request $request)
    {
            
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
       
 
           $auUser=Tck_user::findOrFail($user->id);
        $dayoff= new Tck_dayoff
     ([
         'usrCode'=> $auUser->code,
         'fromDate' => $request->get('fromDate'),
         'fromTime'=>$request->get('fromTime'),
         'toDate' => $request->get('toDate'),
          'toTime'=>$request->get('toTime'),
          'usrUDP'=>$user->id,
          'status'=>1,
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
        $dayoffUdp->status=3;
        $dayoffUdp->lNote=$request->input('note');
         $dayoffUdp->save();
         }


       
     
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

      $timeleave=0;
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
        $dayoffUdp = Tck_dayoff::findOrFail($id);
        if($dayoffUdp->status==1){
        $tck_user=Tck_user::where('code','=',$dayoffUdp->usrCode)->get()->first();
        $tck_workinghours= Tck_workinghours::findOrFail($tck_user->whID);

        $dayHOs=$this->checkHoliday($dayoffUdp->fromDate,$dayoffUdp->toDate);
        
       
       $timeleave=0;
       if ($dayoffUdp->days==0) {

         $timeleave=$this->countLeave(
          $tck_workinghours->hour1, $tck_workinghours->hour2, $tck_workinghours->hour3
          , $tck_workinghours->hour4,$dayoffUdp->fromTime, $dayoffUdp->toTime);
          $tck_leaveOne = new Tck_annualleave([
                'usrID'=>$dayoffUdp->usrCode,
                'date'=>$dayoffUdp->fromDate,
                'from'=>$dayoffUdp->fromTime,
                'to'=>$dayoffUdp->toTime,
              ]);
          $tck_leaveOne ->save();

          //
            if ($tck_user->compenTime>$timeleave) {
          $tck_user->compenTime=$tck_user->compenTime-$timeleave;
        } else{
          
            $tck_user->usTime=$tck_user->usTime+$tck_user->compenTime - $timeleave;
             $tck_user->compenTime =0;
        }
        $dayoffUdp->status=2;$dayoffUdp->save();
        $tck_user->save();
       

       } else
       {       
       foreach ($dayHOs as $dayHO => $value) {            
            if ($value->typeName=='WO') {
              $tck_annualleave = new Tck_annualleave([
                'usrID'=>$dayoffUdp->usrCode,
                'date'=>$value->date,
                'from'=>$tck_workinghours->hour1,
                'to'=>$tck_workinghours->hour2,
              ]);
               $tck_annualleave->save();
            }
           
         }      
        // create dayoff for first and end days
         $tck_leavef = new Tck_annualleave([
                'usrID'=>$dayoffUdp->usrCode,
                'date'=>$dayoffUdp->fromDate,
                'from'=>$dayoffUdp->from,
                'to'=>$tck_workinghours->hour2,
              ]);
               $tck_leavef->save();
          $tck_leavee = new Tck_annualleave([
                'usrID'=>$dayoffUdp->usrCode,
                'date'=>$dayoffUdp->toDate,
                'from'=>$tck_workinghours->hour1,
                'to'=>$dayoffUdp->to,
              ]);
        $tck_leavee->save();
        // tinh toan thoi gian nghi phai tru.
        $firstTime=$this->countLeave($tck_workinghours->hour1,$tck_workinghours->hour2
          ,$tck_workinghours->hour3,$tck_workinghours->hour4,$dayoffUdp->fromTime
          ,$tck_workinghours->hour2);
        $lastTime=$this->countLeave($tck_workinghours->hour1,$tck_workinghours->hour2
          ,$tck_workinghours->hour3,$tck_workinghours->hour4
          ,$tck_workinghours->hour1
          ,$dayoffUdp->toTime);
         
        $timeleave=$dayoffUdp->days*480+$firstTime+$lastTime;
        if ($tck_user->compenTime>$timeleave) {
          $tck_user->compenTime=$tck_user->compenTime-$timeleave;
        } else{
          
            $tck_user->usTime=$tck_user->usTime+$tck_user->compenTime - $timeleave;
             $tck_user->compenTime =0;
        }
        $dayoffUdp->status=2;$dayoffUdp->save();
        $tck_user->save();
       
      }
      }
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
    private  function checkHoliday ($from,$to)
    {
    $tck_dayyear=DB::table('Tck_dayyear')
    ->where('date','>',$from)
     ->where('date','<',$to)
    
    ->get();
    $result=$tck_dayyear->keyBy('date');
    
    return $result;
    }
     private  function countLeave ($hour1,$hour2,$hour3,$hour4,$start,$end)
     {
      $sumtime=0;
       
        $chour1 =  Carbon::parse($hour1);
        $chour2 =  Carbon::parse($hour2);
        $chour3 =  Carbon::parse($hour3);
        $chour4 =  Carbon::parse($hour4);
        $cstart =  Carbon::parse($start);
        $cend   =  Carbon::parse($end);
        $time=0;

        /// COUNT TIME FROM HOUR1 TO  hour3
      
        if ($chour1 > $cend or $cstart>$chour2) {

          return $sumtime;

        } else  {
         
           $sumtime=$this->timeSubtract($chour1,$chour3,$cstart,$cend);
            $sumtime+=$this->timeSubtract($chour4,$chour2,$cstart,$cend);

          
        }
         
        return $sumtime;


     }
      private  function timeSubtract($chour1,$chour2,$cstart,$cend)
      { 
         $time1=$chour1;
         $time2=$cend;

        if ( $cstart<$chour1) {
            $time1=$chour1;

          } else
          {
             $time1=$cstart;
          }
          if ( $cend>$chour2) {
            $time2=$chour2;
          } else
          {
             $time2=$cend;
          }
             
        return $time2->diffInMinutes($time1);

      }


}