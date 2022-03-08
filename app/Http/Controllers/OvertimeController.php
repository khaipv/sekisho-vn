<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Tck_master;
use App\Tck_dayoff;
use App\Tck_dayoffb;
use App\Tck_user;
use App\Division;
use App\Tck_dayyear;
use App\Tck_annualleave;
use App\Tck_workinghours;
use App\Library\CommonTimeFunction;
use DB;
use App\User;
use App\Tck_overtime;
use Carbon\Carbon;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
class OvertimeController extends Controller
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
      const  OT_CR_CPDO=403;
    const OT_CR_ANNUAL=201;
    const OT_CR_CP=202;
    const OT_CR_SPECIAL=203;
     const OT_CR_UNP=204;
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
        $masterDO=$master;
      $userH = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.code','=','users.code')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest')
                    ->where("tck_user.code","=",  $user->code)
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

        return view('overtime.overtime', compact('master','userH','days','rest','masterTerm','masterFrom','masterTo','dayAlls','masterDO'));
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
    $tck_dayyear=DB::table('tck_dayyear')
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
        /// COUNT TIME FROM HOUR1 TO  hou
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
      public function updateOT(Request $request)
    {
      try {
          $userH=$this->getUsr();
      $dateArr=$fromTime=$toTime=$note=array();
      $dateArr=$request->date;
      $fromTime=$request->fromTime;
      $toTime=$request->toTime;
      $note=$request->note;
      $daylist=$this->getOTDay(current($dateArr),end($dateArr),$userH->code);
      
          $i=0;
       //   $this->validateOT($request);
      foreach ($daylist as $key ) {
        if ( ($key->status ==1 && !is_null($fromTime[$i])&& !is_null($toTime[$i]) ) || (is_null($key->status)&& !is_null($fromTime[$i])&& !is_null($toTime[$i]) ) ) {
            $item=Tck_overtime::updateOrCreate(
              [
              'code'=>$userH->code,
              'date'=>$key->date,
              ],
                           [
                            'start'=>$fromTime[$i],
                            'end'=>$toTime[$i],  
                            'note'=>$note[$i] ,   
                            'status'=>1,                      
                           ]);
                          $item->save();
        }
        $i++;
      }
      } catch (Exception $e) {
      }
      return back();
    }
    private function getUsr()
    {
             $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
      $userH = DB::table('tck_user')
                  ->leftjoin('users', 'tck_user.id','=','users.id')
                   ->select('tck_user.*')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();
                    return $userH;
    }
    private function getOTDay($from,$to,$code)
    {
           $days = DB::table('tck_dayyear as dy')
          ->leftjoin('tck_overtime as ot', 'ot.date', '=',
           DB::raw('dy.date and ot.code='.$code))
              -> whereBetween('dy.date',[$from, $to])
           ->selectRaw('dy.*, DATE_FORMAT(ot.start, "%H:%i") start   , 
            DATE_FORMAT(ot.end, "%H:%i") end ,ot.status')
           ->orderBy('date','ASC')
           ->get();
           return $days;
    }
    public function otDaySearch(Request $request)
    {
      $request->flash();
      $userH = $this->getUsr();
      $days=$this->getOTDay($request->fromDates,$request->toDates,$userH->code);
      return view('overtime.overtime', compact('userH','days'));

    }
     

        public function showOT(Request $request)
    {     
        if ($request->ajax()) {
            $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
      $master=Tck_master::all();
     $userH = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.id','=','users.id')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();
        $t =  Carbon::createFromFormat('Y-m-d', $request->id);
          // $t =  Carbon::parse('11/06/1990')->format('d/m/Y');
          $days = DB::table('tck_dayyear as dy')
          ->leftjoin('tck_print as pr', 'pr.date', '=',
           DB::raw('dy.date and pr.code='.$userH->code))
          ->leftjoin('tck_companies', 'pr.companycode','=','tck_companies.code')
          ->whereRaw("year(dy.date)=".  $t->year)
          ->whereRaw("month(dy.date)=".  $t->month)
           ->whereRaw("day(dy.date)=".  $t->day)
          ->selectRaw('dy.*,pr.attendance,pr.leaving,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest')
         ->first();
      
       // Tính ra thời gian nghỉ rest nếu attendance,leaving nằm giữa rest time thì set rest=1:00
     

           return response()->json($days);
        }
    }
    public function createOT(Request $request)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }     


          $type=$request->get('selectApp');  

             $auUser=User::findOrFail($user->id);
            
                    if ($type==self::OT_CR_CODE) {
           
      //OT_CR_STATUS

      $this->validateOT($request->get('fromOT'),$request->get('toOT'),$request, $auUser->code);
        CommonTimeFunction::createOT($request);

        return redirect('application')->with('message', 'Succes Data was created');
          } elseif ($type==self::OT_CR_CPDO) {

            $this->createDayOff($request);
             
              return redirect('application')->with('message', 'Succes Data was created');
          } elseif ($type==self::OT_CP_STATUS) {
            $this->validateHO($request, $auUser->code);
            $this->createHO($request);
             return redirect('application')->with('message', 'Succes Data was created');
          }
          
    }
      public function createLeave(Request $request)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }          
            $auUser=Tck_user::findOrFail($user->id);
       $this->validateOT($request->get('fromOT'),$request->get('toOT'),$request, $auUser->code);
       // check unique
       $leaveValidate = DB::table('tck_overtime')
          ->where("statusCP","=",self::OT_LEAVE_CODE)
          ->where("usrID","=", $auUser->code)
          ->where("date","=", $request->get('dateOT'))
          ->where("start","=",$request->get('fromOT'))
          ->where("end","=", $request->get('toOT'))
          ->where("statusCP","=", self::OT_LEAVE_CODE)
          ->first();
          if (!is_null($leaveValidate)) {
           return Redirect::back() ;
          }
       // end check unique
        CommonTimeFunction::createLeave($request);
       $leaveObj = DB::table('tck_overtime')
          ->where("statusCP","=",self::OT_LEAVE_CODE)
          ->where("usrID","=", $auUser->code)
          ->orderBy('id','DESC')
          ->first();
      //OT_CR_STATUS
     
       // $this->detailLeave(self::OT_LEAVE_CODE);
         $days = DB::table('tck_dayyear as dy')
          ->leftjoin('tck_print as pr', 'pr.date', '=',
           DB::raw('dy.date and pr.code='.$auUser->code))
          ->where("dy.date","=", $leaveObj->date)
          ->selectRaw('dy.*,pr.attendance,pr.leaving')
         ->first();
          $rest='0';
                $auUser = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.id','=','users.id')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
          ->leftjoin('tck_user as ma','ma.id','=','tck_user.ma')
          ->leftjoin('tck_user as mb','mb.id','=','tck_user.mb')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest,ma.name as maName,mb.name as mbName')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();
       // Tính ra thời gian nghỉ rest nếu attendance,leaving nằm giữa rest time thì set rest=1:00
      if ($days->attendance< $auUser->restE && $days->leaving< $auUser->restE) {
         $rest=$auUser->rest;
      }
      return view ('overtime.detailLeave',compact('leaveObj','days','rest','auUser'))->with('message', 'Succes! Request was created') ;
    }
      public function createHO(Request $request)
    {
          CommonTimeFunction::createHO($request);
          return redirect('application');
    }
      public function createDayOff(Request $request)
    { 

       $this->validateDayoff($request);

      CommonTimeFunction::createNewDayOff( $request);
     
       return redirect('application');
      }
      /// Truong hop nhieu ngay, insert 1 ngay thanh 2 row: AM,PM    
       public function validateHO($request,$userCode)
       {
        if(is_null($request->input('dateHO') )) {
           $this->validate($request,[
                'dateHO' => 'required|email',
        ],[
         'Date is empty '
        ]);

        }
       }
      public function validateOT($from,$to,$request,$userCode)
       {
       DB::connection()->enableQueryLog();
        if ($from>$to) {
                $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' Wrong time overtime input '
        ]);
        }
        if (is_null($request->input('dateOT')) || is_null($request->input('fromOT'))
        || is_null($request->input('toOT'))  ) {
                $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' Date,from,to: not empty '
        ]);
        }
          $dateOT = Carbon::parse($request->input('dateOT'));
          $from="'".$from.":00'";
          $to="'".$to.":00'";
          $tblOT=DB::table('tck_overtime') ->where('date','=',$dateOT) ->where('usrID','=',$userCode)
          ->whereRaw( "( ( tck_overtime.start < ". $from . " and  tck_overtime.end > ". $from ." ) or (
           tck_overtime.start < ". $to . " and  tck_overtime.end > ". $to .") )" ) ->count();
      
           //   $query = DB::getQueryLog();
           //    $lastQuery = end($query);
           // dd(  $lastQuery);
          if ($tblOT>0) {
            $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' Duplicate value  '
        ]);
          }

       }
          public function validateDayoff($request)
       {
          $user = Auth::user();
            $from =  Carbon::parse($request->get('dateDOFrom'));
             $now = Carbon::now();
          $userH = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.code','=','users.code')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest, users.role as role')
                    ->where("tck_user.code","=",  $user->code)
                    ->first();
           $auUser;
                 if (is_null($request->get('dateDOFrom'))) {
              $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' From Date is empty '
        ]);
            }
           $from=Carbon::parse($request->get('dateDOFrom'));
      
           
        $auUser = DB::table('tck_user')
                   ->select('tck_user.*')
                    ->where("code","=",  $user->code)
                  
                    ->where("year","=", $from->year)
                    ->first(); 
                               
         $from = $request->get('dateDOFrom');
         $to =  $request->input('dateDOTo');
          $fromTerm =  $request->get('termFrom');
         $toTerm = $request->input('termTo');
         $doType=$request->input('doType');
          $countCPDO= CommonTimeFunction::fcn_CountDate($from,$fromTerm,$to,$toTerm);
         if (!is_null($request->input('dateDOTo'))) {
                 if ($from>$to) {
                $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' Wrong time overtime input '
        ]);
        }
        if ($from==$to ) {
            $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' If you set dayoff in 1 day only, please set To Date is blank '
        ]);
        }
         }

           if (is_null($request->input('dateDOTo'))) {
        $to=$from;
        $toTerm=$fromTerm;
      }
         $toDate = Carbon::parse($to);
       $fromDate= Carbon::parse($from);
       if ($toDate->dayOfWeek==0 ||$toDate->dayOfWeek==6
           || $fromDate->dayOfWeek==0 ||$fromDate->dayOfWeek==6) {
             $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' You have chosen weekend days '
        ]);
       }

             $dayoffbLst= DB::table('tck_dayoffb')->join('tck_dayoff','tck_dayoffb.doID','=','tck_dayoff.id') 
      ->where('tck_dayoff.status','<>',self::OT_DELETE_CP)
      ->where('tck_dayoff.status','<>',self::MAN_DENY_1)
      ->where('tck_dayoff.status','<>',self::MAN_DENY_2)
        ->where('tck_dayoff.usrCode','=', $userH->code)
      ->whereBetween ('tck_dayoffb.date',[$from,$to]) 
      ->select('tck_dayoffb.amID','tck_dayoffb.pmID','tck_dayoffb.date')
      ->get();
     
      foreach ($dayoffbLst as $key => $dayoffb) {
        if ( ( $dayoffb->date > $from && $dayoffb->date <$to )
        || (  $dayoffb->date == $from  && (
          ( $dayoffb->amID <> 0 &&  $dayoffb->pmID<>0)
         || ($fromTerm == 503)
         || ($dayoffb->amID <> 0 && $fromTerm == 501)
         || ($dayoffb->pmID <> 0 && $fromTerm == 502)
         || ($dayoffb->pmID <> 0 && $dayoffb->date < $to)

        ) 

         )
        || (  $dayoffb->date == $to  && (
            ( $dayoffb->amID <> 0 &&  $dayoffb->pmID<>0)
         || ($toTerm == 503)
         || ($dayoffb->amID <> 0 && $toTerm == 501)
         || ($dayoffb->pmID <> 0 && $toTerm == 502)
         || ($dayoffb->amID <> 0 && $dayoffb->date > $from)
        ))
          ) {
           $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' Duplicate Dayoffs '
        ]);
        }
      }
      // Check validate dayoff (new )
      // Dayoff tern create in 1 month, not from 10 to 11
      if ($toDate->month <> $fromDate->month) {
           $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' From Date And To Date is in 1 month'
        ]);
        }
      // check validate CP
      // get dayoff CP  
          $dayOffs =  DB::table('tck_overtime as ot')
       ->where('ot.usrID','=',$userH->code)
       ->whereRaw("year(ot.date)=".  $fromDate->year)
       ->whereRaw("month(ot.date)=".  $fromDate->month)
       ->where('statusCP','=','402')
       ->where('ot.status','<>','404')
        ->where('ot.status','<>','103')
         ->where('ot.status','<>','104')
      ->select(['ot.oriNum as oriNum','ot.datenum as datenum','ot.date as date']) ->orderBy('date','ASC') ->get();
      $cpDay=0;
      foreach ($dayOffs as $key => $value) {
        if ( !is_null($value->datenum) && $value->datenum>0 ) {
          $cpDay+=$value->datenum;
        }
        }
        if ($doType==self:: OT_CR_CP) {
      
       
        // check neu so ngay xin nghi phep > so ngay nghi bu thi bao loi

        if ($cpDay< $countCPDO) {
          $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' Not enought Conpensation Day'
        ]);
        }
      } elseif ($doType==self:: OT_CR_ANNUAL && $auUser->annualLeaveDate <$countCPDO) {
        // validate annual auUser->annualLeaveDate
        $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' Not enought Annual Day'
        ]);
        
      }
      // check neu con ngay nghi bu thi chi duoc xin phep nghi bu
      if ($cpDay>0 && $doType <> self:: OT_CR_CP ) {
      $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' When still have Conpensations, you can not create other type Dayoff'
        ]);
      }

      // check con ngay annual nhung create unpaid
      if ( $doType==self:: OT_CR_UNP &&  $userH->annualLeaveDate >0 )
      {
              $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' You still have Annual Dayoffs'
        ]);
      }

      // end check unpaid


      // End Check validate dayoff (new )

 
       }
       //End validate Dayoff
       private function insertTKdayoffB($doID,$date,$amID,$pmID)
       {
          $tck_dayoffb=Tck_dayoffb::updateOrCreate(
                          [
              'doID' => $doID,
              'date' => $date,
              'type' => 'CP',
              'status' => '101',
              'amID' => $amID,
              'pmID' => $pmID,
                          ]);
            $tck_dayoffb->save(); 
       }
       private function updateOvertime($dateOTlist){
        for ($f=0; $f <sizeof($dateOTlist) ; $f++) { 
          $hoUDP=tck_overtime::findOrFail($dateOTlist[$f]->id);
          $hoUDP->datenum=$dateOTlist[$f]->datenum;
          $hoUDP->save();
        }
       // $tck_user=tck_user::findOrFail($auUser->id);
       }
        public function detailLeave()
    {
        $leaveObj = DB::table('tck_overtime')
          ->where("statusCP","=",self::OT_LEAVE_CODE)
          ->where("usrID","=", $auUser->code)
          ->orderBy('id','DESC')
          ->first();
      //OT_CR_STATUS
     
       // $this->detailLeave(self::OT_LEAVE_CODE);
         $days = DB::table('tck_dayyear as dy')
          ->leftjoin('tck_print as pr', 'pr.date', '=',
           DB::raw('dy.date and pr.code='.$auUser->code))
          ->where("dy.date","=", $leaveObj->date)
          ->selectRaw('dy.*,pr.attendance,pr.leaving')
         ->first();
          $rest='0';
                $auUser = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.id','=','users.id')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
          ->leftjoin('tck_user as ma','ma.id','=','tck_user.ma')
          ->leftjoin('tck_user as mb','mb.id','=','tck_user.mb')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest,ma.name as maName,mb.name as mbName')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();
       // Tính ra thời gian nghỉ rest nếu attendance,leaving nằm giữa rest time thì set rest=1:00
      if ($days->attendance< $auUser->restE && $days->leaving< $auUser->restE) {
         $rest=$auUser->rest;
      }
      return view ('overtime.detailLeave',compact('leaveObj','days','rest','auUser'))->with('message', 'Succes! Request was created') ;
     
    }

}