<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;
use App\User;
use App\Tck_master;
use Illuminate\Support\Facades\Auth;
use App\Tck_workinghours;
use App\Tck_overtime;
use App\Tck_print;
use App\Tck_user;
use App\Tck_dayyear;
use App\Tck_dayoff;
use App\Tck_dayoffb;
use App\Library\CommonTimeFunction;
use Illuminate\Support\Facades\Input;
use PDF;
use App\Tck_user_leave;
class TimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    const OT_CR_STATUS  =101; 
    const OT_CP_STATUS  =402; 
    const OT_CR_CODE  =401; 
    const OT_CR_ANNUAL=201;
    const OT_CR_CP=202;
    const OT_CR_SPECIAL=203;
     const OT_DELETE_CP=404;
         const MAN_DENY_1=103;
    const MAN_DEN_2=104;
    const MAN_APP_1=105;
    const MAN_APP_2=106;
        const OFF_TYPE_MOR=501;
    const OFF_TYPE_AFT=502;
    const OFF_TYPE_ALL=503;
    public function index()
    {
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         $users= $this->getUserlist($user);
         //

         // end test
          CommonTimeFunction::fcn_UdpAnualLeave();
          $t = Carbon::now();
        $master = Tck_master::sortable()
            ->orderBy('id', 'desc')
            ->sortable()
            ->paginate(10);
       $userH = DB::table('tck_user')
                  ->leftjoin('users', 'tck_user.code','=','users.code')
                   ->select('users.*','tck_user.*')
                    ->where("tck_user.code","=",  $user->code)
                     ->where("tck_user.year","=",  $t->year)
                    ->first();
                                     
           $annualLeaveYear =     $userH->annualLeaveDate;
         $userL= $userH;  
         $minus=0;    
 $OTlist2;
        $tck_user=DB::table('users')
                 ->selectRaw("users.id,CONCAT(users.name,'-',users.code) as name") 
                 ->get();
       $queryI = Tck_print::query();  //Initializes the query
                 $queryI ->leftjoin('tck_workinghours as wh', 'wt_code','=','wh.id')
                 ->leftjoin('tck_dayyear as y', 'tck_print.date','=','y.date')
                 ->join('tck_user as us', 'tck_print.code','=','us.code')
                
                 ->leftjoin('tck_overtime as over', 
                    function($join)
                    {
                        $join->on('tck_print.date', '=', 'over.date');
                        $join->on('tck_print.code', '=', 'over.code');
                        $join->where('over.status', '=', self::MAN_APP_2);
                    })
                    ->select(
                       'tck_print.status', 'tck_print.date','tck_print.id','tck_print.code','us.name','y.nameX', 'attendance_ori','leaving_ori',
                        'attendance','leaving','workingtime','wh.breaks as breaks',
                        DB::raw('subtime(wh.hour1,attendance) as latetime')
                        ,DB::raw('wh.hour1 - attendance as late')
                        ,DB::raw('subtime(leaving,wh.hour2) as ealytime')
                        ,DB::raw('leaving-wh.hour2 early')
                        ,'y.typeName','y.name as typedate'
                        ,'wh.hour1 as hour1'
                        ,'wh.hour2 as hour2'
                        ,'wh.hour3 as hour3'
                        ,'wh.hour4 as hour4'
                        ,'over.start as overStart'
                        ,'over.end  as overEnd'
                        ,'tck_print.companyCode'
                    )
                      ->where("tck_print.code","=",$userH->code)
                      ->where("us.year","=",$t->year)
                      ->whereRaw("year(tck_print.date)=".  $t->year)
                               ->whereRaw("month(tck_print.date)=".  ($t->month) );
                    ;

                      $timesheet=$queryI->orderBy('date', 'ASC')->paginate(50);
                        $workingtimes = DB::table('tck_print')
                                 ->where("code","=",  $userH->code)
                                 ->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC( workingtime))) as workingTime'))->first();

                           
                $ovt='00:00:00';  
        // count overtime compenday 
          $normal_OT=$normal_Midnight=$weekend_WK=$weekend_OT=$weekend_Midnight=$HO_WK=$HO_OT
         =$HO_Midnight =0;  
         // Get all special day
         $tblDayoff = DB::table('tck_dayoff')
                            ->where('type','=',self::OT_CR_SPECIAL)
                             ->whereRaw(" ( month(fromDate)=".  ($t->month).
                              " or month(toDate)=".  ($t->month)." )" )
                             ->where("usrCode","=",$userH->code)
                              ->get();
            

         //
          $queryOT = Tck_overtime::query();  //Initializes the query
                    $queryOT ->join('tck_dayyear as yr', 'tck_overtime.date','=','yr.date')
                             ->join('tck_user as us','us.code','=','tck_overtime.usrID') 
                             ->leftjoin('tck_rankot', 'tck_rankot.typeName','=','yr.typeName')
                             ->select('tck_overtime.date','tck_overtime.start','tck_overtime.end','yr.typeName','tck_overtime.term','tck_overtime.statusCP','tck_rankot.val as otval','tck_overtime.oriNum','tck_overtime.companyCode','tck_overtime.usrID','tck_overtime.oriNum')
                             ->where("us.code","=",$userH->code)
                             ->where("tck_overtime.status","=",self::MAN_APP_2);
                    
                               $queryOT ->whereRaw("year(yr.date)=".  $t->year)
                               ->whereRaw("month(yr.date)=".  ($t->month));
                             
                              // getworking time from userH

                      $tck_workinghours=Tck_workinghours::find($userH->whID);    
                     
                      if (!is_null($tck_workinghours)) {
                        //start count OVTime, wk OT, HO OT
                        $OTlist= $queryOT->orderBy('date', 'ASC')->get();
                       
                        $OTlist2=$OTlist;
                       
                        foreach ($OTlist as $key => $value) {
                            // if OVer time type= 401  OT_CR_CODE
                        if ($value->statusCP==self::OT_CR_CODE) {
                            // Case normal 
                            if ($value->typeName=='WO') {
                            $normal_OT+=$this->timeSubtract($tck_workinghours->ohour1,$tck_workinghours->ohour2,$value->start,$value->end);
                            $normal_OT+=$this->timeSubtract($tck_workinghours->ohour5,$tck_workinghours->ohour6,$value->start,$value->end);
                            $normal_Midnight+=$this->timeSubtract($tck_workinghours->ohour2,$tck_workinghours->ohour3,$value->start,$value->end);
                            $normal_Midnight+=$this->timeSubtract($tck_workinghours->ohour4,$tck_workinghours->ohour5,$value->start,$value->end);
                            }
                            //Case weekend
                            if ($value->typeName=='WK') {
                            $weekend_OT+=$this->timeSubtract($tck_workinghours->ohour1,$tck_workinghours->ohour2,$value->start,$value->end);
                            $weekend_OT+=$this->timeSubtract($tck_workinghours->ohour5,$tck_workinghours->ohour6,$value->start,$value->end);
                            $weekend_Midnight+=$this->timeSubtract($tck_workinghours->ohour2,$tck_workinghours->ohour3,$value->start,$value->end);
                            $weekend_Midnight+=$this->timeSubtract($tck_workinghours->ohour4,$tck_workinghours->ohour5,$value->start,$value->end);
                            }
                            //Case holiday
                            if ($value->typeName=='HO') {
                            $HO_OT+=$this->timeSubtract($tck_workinghours->ohour1,$tck_workinghours->ohour2,$value->start,$value->end);
                            $HO_OT+=$this->timeSubtract($tck_workinghours->ohour5,$tck_workinghours->ohour6,$value->start,$value->end);
                            $HO_Midnight+=$this->timeSubtract($tck_workinghours->ohour2,$tck_workinghours->ohour3,$value->start,$value->end);
                            $HO_Midnight+=$this->timeSubtract($tck_workinghours->ohour4,$tck_workinghours->ohour5,$value->start,$value->end);
                            }
                               
                            }    
                            // end if OT begin if Compensation Date
                           if ($value->statusCP==self::OT_CP_STATUS) {
                            
                             if ($value->typeName=='WK') {
                                $weekend_WK+=( $value->oriNum/$value->otval )*8;
                                
                            }
                               if ($value->typeName=='HO') {
                                $HO_WK+=( $value->oriNum/$value->otval )*8;
                                
                            }
                            // end if OT-Compensation Date                            
                        }
                        // end count OVTime, wk OT, HO OT
                         }   
                      //end working hour1      
                     } 
                    $queryHoliday = tck_dayyear::query(); 
                    $queryHoliday->select('typeName');
                      $queryHoliday->where('typeName','=','HO');
                     
                      
                      $timesheet=$queryI->orderBy('date', 'ASC')->paginate(50);
                      $workingtimes =null;
                     if (!is_null($userH)) {
                               $workingtimes = DB::table('tck_print')
                                 ->where("code","=",  $userH->code)
                                 ->whereRaw("year(tck_print.date)=".  $t->year)
                               ->whereRaw("month(tck_print.date)=".  ($t->month))
                                 ->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC( workingtime))) as workingTime'))->first();
                            $queryHoliday->whereRaw("year(date)=".  $t->year)
                               ->whereRaw("month(date)=".  ($t->month))
                               ->get();

                     } 
                       // count early and late
               $lateE=0;
    foreach ($timesheet as $key => $value) {
        $hour1=Carbon::parse($tck_workinghours->hour1);
        $hour2=Carbon::parse($tck_workinghours->hour2);

      if (!is_null($value->attendance)) {
        $attendance=Carbon::parse($value->attendance);
        if ($attendance>$hour1) {
            $lateE+=$this->timeLate($hour1,$attendance);
        }
    }
     if (!is_null($value->leaving)) {
        $leaving=Carbon::parse($value->leaving);
        if($leaving<$hour2)
        {
            $lateE+=$this->timeLate($leaving,$hour2);
        }
    }
        
    }          
     

        // end count overtime compenday              
 $normal_OT_H =$this->timeShowTXT($normal_OT);
    $normal_Midnight_H= $this->timeShowTXT($normal_Midnight);
    $weekend_OT_H = $this->timeShowTXT($weekend_OT);
    $weekend_Midnight_H= $this->timeShowTXT($weekend_Midnight);
   
    
    $weekend_WK_Hour=  $weekend_WK*60 + $weekend_OT+ $weekend_Midnight;
    $weekend_WK_H=  $this->timeShowTXT($weekend_WK_Hour);
    $HO_Midnight_H=$this->timeShowTXT($HO_Midnight);
     $HO_OT_H = $this->timeShowTXT($HO_OT);
    $holiday_WK_Hour=  $HO_WK*60 + $HO_OT+ $HO_Midnight;
    $HO_WK_H= $this->timeShowTXT($holiday_WK_Hour);
      $lateE_H = floor($lateE/60).':'.($lateE - floor($lateE / 60) * 60);   




             // Start count số ngày còn lại      
             $monthsearch=$t;      
                     $dayoffsQuery=Tck_dayoffb::query();
                     $dayoffsQuery ->join('tck_dayoff', 'tck_dayoffb.doID','=','tck_dayoff.id')
                     ->selectRaw("tck_dayoffb.date,tck_dayoffb.amID,tck_dayoffb.pmID,month(tck_dayoffb.date) as month,tck_dayoff.status as status")
                     ->where("tck_dayoff.usrCode","=",$userH->code)
                     ->where("tck_dayoff.status","<>",self::OT_DELETE_CP)
                     ->where("tck_dayoff.status","<>",self::MAN_DENY_1)
                     ->where("tck_dayoff.status","<>",self::MAN_DEN_2)
                     ->whereRaw("year(tck_dayoffb.date) =".  $monthsearch->year)
                     ->whereRaw("month(tck_dayoffb.date) >=".  ($monthsearch->month));
                     $dayoffList=$dayoffsQuery ->get();
                     // $dayoffNumber in month and dayoff number next month
                     $alldayoffNumber=$dayoffPreNumber=0;
                      foreach ($dayoffList as $key => $value) {
                         if ($value->amID<0) {
                           $alldayoffNumber +=0.5;
                           if ($value->month>$monthsearch->month) {
                               $dayoffPreNumber+=0.5;
                           }
                         }
                            if ($value->pmID<0) {
                           $alldayoffNumber +=0.5;
                           if ($value->month>$monthsearch->month) {
                               $dayoffPreNumber+=0.5;
                           }
                         }
                     }
                       // End count số ngày còn lại  
                     $year=$t->year;
                     $month=$t->month;
       $lateArray=  array('01:00' ,
                           '02:00' 
                               );
          // start count late test
       
        $arrayWorkHour=$this->fcn_WorkHourLST($timesheet,$tck_workinghours,$OTlist2,$dayoffList, $tblDayoff);
        
        $arrayOVT     =$this->fcn_OverTimeLST($timesheet,$OTlist2,$tck_workinghours);
        $arrayTimelate=$this->fcn_timeLate($timesheet,$arrayWorkHour,$dayoffList,$arrayOVT,$tck_workinghours, $tblDayoff);
        $arrayTotal =array('sumWorkHour' =>$this->timeShowTXT( end($arrayWorkHour) )
                            ,'sumOVT' =>$this->timeShowTXT( end($arrayOVT))
                            ,'sumTimelate' =>$this->timeShowTXT(end($arrayTimelate))
                             );
            $arrayTime = array(
      "d1"=> $this->timeShowTXT( end($arrayWorkHour) -$weekend_WK_Hour-$holiday_WK_Hour)
      ,"d2"=>$normal_OT_H
      ,"d3"=>$normal_Midnight_H
      ,"w1"=>$weekend_WK_H
      ,"w2"=>$weekend_OT_H
      ,"w3"=>$weekend_Midnight_H
      ,"h1"=>$HO_WK_H
      ,"h2"=>$HO_OT_H
      ,"h3"=>$HO_Midnight_H
      ,"wSum"=> $weekend_WK_Hour
      ,"hoSum"=> $holiday_WK_Hour
      ); 

        // end count late test
                 $arrayWorkHour8h=$this->fcn_WorkHourLST8h($timesheet,$tck_workinghours,$OTlist2,$dayoffList, $tblDayoff);
                $arrayLeave= $this->fcn_countDay($dayoffList,$month,$annualLeaveYear,$minus);
               
          return view ('timesheet.index',compact('timesheet','tck_user','userH'
            ,'lateE_H','userL','tck_workinghours','OTlist2','alldayoffNumber','dayoffPreNumber','month','year','arrayTime','lateArray','arrayTimelate','arrayWorkHour','arrayOVT','arrayTotal','minus','dayoffList','annualLeaveYear','arrayWorkHour8h','arrayLeave'));
    }

    /**,
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
       public function getUserlist($user)

    {
         $userLst;
        if ($user->role==2) {
           $userLst = User::all();
        }elseif ($user->role==1) {

            $userLst = User::where('group','=',$user->group)->get();
        } elseif ($user->role==0) {
           $userLst = $user;
        }
            return $userLst;
        
    }
    public function timeSearch(Request $request)
    {
           $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
           $users= $this->getUserlist($user);
          if (is_null($this->getUserlist($user))) {
               $user = Auth::user();
          }
        
         $from=null;
         $year=$request->input('year');
         $month=$request->input('month');
         if (!is_null($request->input('year'))) {
            $from= $request->input('year');
         }
           if (!is_null($request->input('toDates'))) {
          // $to= Carbon::createFromFormat('Y-m',$request->input('toDates'));
       }
         DB::connection()->enableQueryLog();
                  // Get all special day
           
       
                          
          $lateE=0;

          $t = Carbon::now();
         $normal_OT=$normal_Midnight=$weekend_WK=$weekend_OT=$weekend_Midnight=$HO_WK=$HO_OT
         =$HO_Midnight =0;
            $request->flash();
      
        $master = Tck_master::sortable()
            ->orderBy('id', 'desc')
            ->sortable()
            ->paginate(10);
        $sID= $request->get('tck_user');
          $userL = DB::table('tck_user')
                  ->join('users', 'tck_user.code','=','users.code')
                   ->select('users.*','tck_user.*','users.id as ids')
                    ->where("users.code","=",  $user->code)
                    ->first();
        if ( $userL->role==0) {
           $sID=$userL->ids;
        }
       $userH = DB::table('tck_user')
                  ->join('users', 'tck_user.code','=','users.code')
                   ->select('users.name','users.joinDate','tck_user.annualLeaveDate','tck_user.code',
                    'tck_user.depart')
                    ->where("tck_user.year","=",  $year)
                     ->where("users.id","=",  $sID)
                    
                    ->first();
            if (is_null($userH)) {
                      $userH = DB::table('tck_user')
                  ->join('users', 'tck_user.code','=','users.code')
                   ->select('users.name','users.joinDate','tck_user.annualLeaveDate','tck_user.code',
                    'tck_user.depart')
                    
                     ->where("tck_user.id","=",  12) ->first();
                    }        
                 if (!is_null($userH)) {
                  $annualLeaveYear= $userH->annualLeaveDate;
                 } else  $annualLeaveYear=0;
       
        if ($year < $t->year && !is_null($userH)) {
          $tck_user_leave = DB::table('tck_user')
                   ->select('tck_user.*')
                    //->where("idCode","=",  $sID)
                    ->where("code","=",  $userH->code)
                    ->where("year","=",  $year)
                    ->first();
                    $annualLeaveYear=$tck_user_leave->annualLeaveDate;
                   }            
         // minus là số tháng phải trừ trong trường hợp user chưa đủ 1 năm
             
         $minus=0;
           $tblDayoff = DB::table('tck_dayoff')
                            ->where('type','=',self::OT_CR_SPECIAL)
                             ->whereRaw(" ( month(fromDate)=".  $month .    
                               " or month(toDate)=".  $month." )" )
                             ->where("usrCode","=",$userH->code)
                              ->get();
                             $joinDateC= Carbon::parse( $userH->joinDate);
                              
        if ((  $year-$joinDateC->year) *12 +$month - $joinDateC->month  >=0 
           && (  $year-$joinDateC->year) *12 +$month - $joinDateC->month  <12
         
      ) { 

        	
           if ($year < $t->year) {
            if ($month<12) {
              $minus=13 - $month;
            } else
            $minus=12 - $month;
          } else 
                { 
                      
                  // Nếu tìm kiếm trong năm nay
                     if (   $t->diffInMonths(Carbon::parse( $userH->joinDate)) <12) {
                     // Thời điểm searh tới joindate nhỏ hơn 12 và hiện tại cũng nhỏ hơn 12 tức chưa cộng    12 ngày 
                       //phép full

                    //case 1 neu searchtime sau ngay duoc cong 
                     // Neu vua vao sau thu viec do duoc cong 2 ngay nen tru di 2, nhung neu da vao tu nam ngoai se chi cong 1 

                      if ( $t->month -$joinDateC ->month >=2 && $t->year == $joinDateC ->year ) {
                        $minus=$t->month - $month  ;
                        if ( $month < $joinDateC ->month +2 ){
                           $minus =$t->month - $month + 2;
                        }
                        
                      }  elseif ( $t->month -$joinDateC ->month >=2 && $t->year > $joinDateC ->year ) {
                         $minus =$t->month - $month + 1;
                      }
            
                  } 
                  // elseif ($t->diffInMonths(Carbon::parse( $userH->joinDate)) >= 12) {
                              
                  //    $minus=13 - $month;
                  //      }
                  //   Trong tháng 12 newbie được cộng 2 ngày phép do vậy nếu truy ngược về thì
                  // phải trừ thêm 1 ngày phép bù nữa vào.  
                  if ($t->month ==12 && $month <> 12) {
                  //	dd("a");
                  	$minus++;
                  	
                  }

         
            
                   }   
          }
                   
         $tck_user=DB::table('users')
                 ->selectRaw("users.id,CONCAT(users.name,'-',users.code) as name") 
                 ->get();
        $queryI = Tck_print::query();  //Initializes the query
                 $queryI ->leftjoin('tck_workinghours as wh', 'wt_code','=','wh.id')
                 ->leftjoin('tck_dayyear as y', 'tck_print.date','=','y.date')
                 ->join('tck_user as us', 'tck_print.code','=','us.code')
                 
                 ->leftjoin('tck_overtime as over', 
                    function($join)
                    {
                        $join->on('tck_print.date', '=', 'over.date');
                        $join->on('tck_print.code', '=', 'over.code');
                         $join->where('over.status', '=', self::MAN_APP_2);
                    })
                    ->select(
                        'tck_print.status','tck_print.date','tck_print.id','tck_print.code','us.name','y.nameX','y.jName' ,'attendance_ori','leaving_ori',
                        'attendance','leaving','workingtime','wh.breaks as breaks',
                        DB::raw('subtime(wh.hour1,attendance) as latetime')
                        ,DB::raw('wh.hour1 - attendance as late')
                        ,DB::raw('subtime(leaving,wh.hour2) as ealytime')
                        ,DB::raw('leaving-wh.hour2 early')
                        ,'y.typeName','y.name as typedate'
                       
                        ,'wh.hour1 as hour1'
                        ,'wh.hour2 as hour2'
                        ,'wh.hour3 as hour3'
                        ,'wh.hour4 as hour4'
                        ,'over.start as overStart'
                        ,'over.end  as overEnd'
                        ,'tck_print.companyCode'
                    );
                    $queryOT = tck_overtime::query();  //Initializes the query
                    $queryHoliday = tck_dayyear::query(); 
                    $queryHoliday->select('typeName');
                    $queryOT ->join('tck_dayyear as yr', 'tck_overtime.date','=','yr.date')
                             ->join('tck_user as us','us.code','=','tck_overtime.usrID') 
                             ->leftjoin('tck_rankot', 'tck_rankot.typeName','=','yr.typeName')
                             ->select('tck_overtime.date','tck_overtime.start','tck_overtime.end','yr.typeName','tck_overtime.term','tck_overtime.statusCP','tck_rankot.val as otval','tck_overtime.companyCode','tck_overtime.usrID','tck_overtime.oriNum')
                            ->where("us.year","=",$t->year)
                             ->where("tck_overtime.status","=",self::MAN_APP_2);
                             $queryOT ->whereRaw("year(yr.date)=".  $from);
                              $queryHoliday->whereRaw("year(date)=".  $from);
                                    if (is_null($from) ) {
                               
                               $queryOT->whereRaw("month(yr.date)=".  $month);
                               
                               $queryHoliday->whereRaw("month(date)=".  $month);
                             } if (!is_null($from)) {
                                $queryOT ->whereRaw("month(yr.date) =". $month );
                                $queryHoliday->whereRaw("month(date) =". $month);
                             } 
                              $tck_workinghours=Tck_workinghours::find(1);    
                    if (!is_null($userH)) {


                         $queryI->where("tck_print.code","=",$userH->code);
                          $queryI->where("us.year","=",$t->year);
                          $queryOT ->where("us.code","=",$userH->code);
                     }

                     // $monthsearch
                     $monthsearch;
                     if (!is_null($from)) {
                                $queryI ->whereRaw("year(tck_print.date)=". $from);
                                $queryI ->whereRaw("month(tck_print.date) =". $month);
                                $monthsearch=$from;
                             } 
                            
                     //  count holiday
                  // Start count số ngày còn lại     
                  $userDayoff=$userH;
                  if (is_null($userH)) {
                             $userDayoff=$user;
                         }       
                     $dayoffsQuery=Tck_dayoffb::query();
                     $dayoffsQuery ->join('tck_dayoff', 'tck_dayoffb.doID','=','tck_dayoff.id')
                     ->selectRaw("tck_dayoffb.date,tck_dayoffb.amID,tck_dayoffb.pmID,month(tck_dayoffb.date) as month ,tck_dayoff.status as status,tck_dayoff.type")
                     ->where("tck_dayoff.usrCode","=",$userDayoff->code)
                     ->where("tck_dayoff.status","<>",self::OT_DELETE_CP)
                     ->where("tck_dayoff.status","<>",self::MAN_DENY_1)
                      ->where("tck_dayoff.status","<>",self::MAN_DEN_2)
                     ->whereRaw("year(tck_dayoffb.date) =".  $monthsearch)
                     ->whereRaw("month(tck_dayoffb.date) >=".  ($month));
                     $dayoffList=$dayoffsQuery ->get();


                     // $dayoffNumber in month and dayoff number next month
                     $alldayoffNumber=$dayoffPreNumber=0;

                     foreach ($dayoffList as $key => $value) {
                         if ($value->amID== -1 || $value->amID== -5 ) {
                           $alldayoffNumber +=0.5;
                           if ($value->month>$month) {
                               $dayoffPreNumber+=0.5;
                           }
                         }
                            if ($value->pmID == -1 || $value->pmID == -5) {
                           $alldayoffNumber +=0.5;
                           if ($value->month>$month) {
                               $dayoffPreNumber+=0.5;
                           }
                         }
                     }
                       // End count số ngày còn lại  

                             
                     // count Over time... 1 get OT list
                   
                   
              
                      // getworking time from userH
                     
                      if (!is_null($tck_workinghours)) {
                        //start count OVTime, wk OT, HO OT
                        $OTlist= $queryOT->orderBy('date', 'ASC')->get();
                        foreach ($OTlist as $key => $value) {
                            // if OVer time type= 401  OT_CR_CODE
                        if ($value->statusCP==self::OT_CR_CODE) {
                            // Case normal 
                            if ($value->typeName=='WO') {
                            $normal_OT+=$this->timeSubtract($tck_workinghours->ohour1,$tck_workinghours->ohour2,$value->start,$value->end);
                            $normal_OT+=$this->timeSubtract($tck_workinghours->ohour5,$tck_workinghours->ohour6,$value->start,$value->end);
                            $normal_Midnight+=$this->timeSubtract($tck_workinghours->ohour2,$tck_workinghours->ohour3,$value->start,$value->end);
                            $normal_Midnight+=$this->timeSubtract($tck_workinghours->ohour4,$tck_workinghours->ohour5,$value->start,$value->end);
                            }
                            //Case weekend
                            if ($value->typeName=='WK') {
                            $weekend_OT+=$this->timeSubtract($tck_workinghours->ohour1,$tck_workinghours->ohour2,$value->start,$value->end);
                            $weekend_OT+=$this->timeSubtract($tck_workinghours->ohour5,$tck_workinghours->ohour6,$value->start,$value->end);
                            $weekend_Midnight+=$this->timeSubtract($tck_workinghours->ohour2,$tck_workinghours->ohour3,$value->start,$value->end);
                            $weekend_Midnight+=$this->timeSubtract($tck_workinghours->ohour4,$tck_workinghours->ohour5,$value->start,$value->end);
                            }
                            //Case holiday
                            if ($value->typeName=='HO') {
                            $HO_OT+=$this->timeSubtract($tck_workinghours->ohour1,$tck_workinghours->ohour2,$value->start,$value->end);
                            $HO_OT+=$this->timeSubtract($tck_workinghours->ohour5,$tck_workinghours->ohour6,$value->start,$value->end);
                            $HO_Midnight+=$this->timeSubtract($tck_workinghours->ohour2,$tck_workinghours->ohour3,$value->start,$value->end);
                            $HO_Midnight+=$this->timeSubtract($tck_workinghours->ohour4,$tck_workinghours->ohour5,$value->start,$value->end);
                            }
                               
                            }    
                            // end if OT begin if Compensation Date
                           if ($value->statusCP==self::OT_CP_STATUS) {
                             if ($value->typeName=='WK') {
                               
                                $weekend_WK+=( $value->oriNum/$value->otval )*8;
                                
                            }
                               if ($value->typeName=='HO') {
                                $HO_WK+=( $value->oriNum/$value->otval )*8;
                                
                            }
                            // end if OT-Compensation Date

                            
                        }

                        // end count OVTime, wk OT, HO OT
                         }   
                      //end working hour1      
                     }
                     $queryHoliday->where('typeName','=','HO')
                     ->get();


     

            //end if exist userH
            $OTlist2= $queryOT->orderBy('date', 'ASC')->get();

                      $timesheet=$queryI->orderBy('date', 'ASC')->paginate(50);  
                                  $query = DB::getQueryLog();
              $lastQuery = end($query);             
           
                      
     
    foreach ($timesheet as $key => $value) {
        //start count late
        if (!is_null($userH)) {
        $hour1=Carbon::parse($tck_workinghours->hour1);
        $hour2=Carbon::parse($tck_workinghours->hour2);

      if (!is_null($value->attendance)) {
        $attendance=Carbon::parse($value->attendance);
        if ($attendance>$hour1) {
            $lateE+=$this->timeLate($hour1,$attendance);
        }
    }
     if (!is_null($value->leaving)) {
        $leaving=Carbon::parse($value->leaving);
        if($leaving<$hour2)
        {
            $lateE+=$this->timeLate($leaving,$hour2);
        }
    }
        
    } 
    }  // end count late
                     // return overtime hour      


    $normal_OT_H =$this->timeShowTXT($normal_OT);
    $normal_Midnight_H= $this->timeShowTXT($normal_Midnight);
    $weekend_OT_H = $this->timeShowTXT($weekend_OT);
    $weekend_Midnight_H= $this->timeShowTXT($weekend_Midnight);
   
    
    $weekend_WK_Hour=  $weekend_WK*60 + $weekend_OT+ $weekend_Midnight;
    $weekend_WK_H=  $this->timeShowTXT($weekend_WK_Hour);
    $HO_Midnight_H=$this->timeShowTXT($HO_Midnight);
     $HO_OT_H = $this->timeShowTXT($HO_OT);
    $holiday_WK_Hour=  $HO_WK*60 + $HO_OT+ $HO_Midnight;
    $HO_WK_H= $this->timeShowTXT($holiday_WK_Hour);
      $lateE_H = floor($lateE/60).':'.($lateE - floor($lateE / 60) * 60);   

 $arrayWorkHour=$this->fcn_WorkHourLST($timesheet,$tck_workinghours,$OTlist2,$dayoffList, $tblDayoff);
 
        
        $arrayOVT     =$this->fcn_OverTimeLST($timesheet,$OTlist2,$tck_workinghours);
        $arrayTimelate=$this->fcn_timeLate($timesheet,$arrayWorkHour,$dayoffList,$arrayOVT,$tck_workinghours , $tblDayoff);

        $arrayTotal =array('sumWorkHour' =>$this->timeShowTXT( end($arrayWorkHour) )
                            ,'sumOVT' =>$this->timeShowTXT( end($arrayOVT))
                            ,'sumTimelate' =>$this->timeShowTXT(end($arrayTimelate))
                             );
            $arrayTime = array(
      "d1"=> $this->timeShowTXT( end($arrayWorkHour)-$weekend_WK_Hour-$holiday_WK_Hour )
      ,"d2"=>$normal_OT_H
      ,"d3"=>$normal_Midnight_H
      ,"w1"=>$weekend_WK_H
      ,"w2"=>$weekend_OT_H
      ,"w3"=>$weekend_Midnight_H
      ,"h1"=>$HO_WK_H
      ,"h2"=>$HO_OT_H
      ,"h3"=>$HO_Midnight_H
      ,"wSum"=> $weekend_WK_Hour
      ,"hoSum"=> $holiday_WK_Hour
      );                          
          //  dd($dayoffList);
            $arrayWorkHour8h=$this->fcn_WorkHourLST8h($timesheet,$tck_workinghours,$OTlist2,$dayoffList, $tblDayoff);

             $arrayLeave= $this->fcn_countDay($dayoffList,$month,$annualLeaveYear,$minus);

                if(Input::get('deny')){
					 $users = User::all();

       
            $pdfName=$year."_".$month."_"."勤務月報"."_".$userH->name."_".$userH->code.".pdf";

                   $pdf = PDF::loadView('timesheet.print',compact('timesheet','tck_user','userH'
            ,'lateE_H','userL','tck_workinghours','OTlist2','alldayoffNumber','dayoffPreNumber','month','year','arrayTime','lateArray','arrayTimelate','arrayWorkHour','arrayOVT','arrayTotal','minus','dayoffList','t','annualLeaveYear','arrayWorkHour8h','arrayLeave'));
                    $pdf->getDomPDF()->set_option('enable_font_subsetting', true);
      return $pdf->download( $pdfName);
                }
        return view ('timesheet.index',compact('timesheet','tck_user','userH'
            ,'lateE_H','userL','tck_workinghours','OTlist2','alldayoffNumber','dayoffPreNumber','month','year','arrayTime','lateArray','arrayTimelate','arrayWorkHour','arrayOVT','arrayTotal','minus','dayoffList','annualLeaveYear','arrayWorkHour8h','arrayLeave'));

 
}

     private  function timeSubtract($time11,$time21,$start1,$end1)

     {
         $time1=Carbon::parse($time11);
        $time2=Carbon::parse($time21);
        $start=Carbon::parse($start1);
        $end=Carbon::parse($end1);
      if ($time2<$start||$time1>$end) {
        return 0;
     } else{
       
        // 
        $from=$time1;
        $to=$time2;
        if ($time1<$start) {
            $from=$start;
        }
         if ($time2>$end) {
            $to=$end;
        }
        return $to->diffInMinutes($from);
     }

     }
    private function timeLate($from,$to)
    {
         $from1=Carbon::parse($from);
        $to1=Carbon::parse($to);
        return ceil( $to1->diffInMinutes($from1)/15)*15;
    }
    private function timeShowTXT($hour)
    {
       if ($hour - floor($hour / 60) * 60 <10) {
            $hour_txt = floor($hour/60).':0'.($hour - floor($hour / 60) * 60);
                 } else 
            $hour_txt = floor($hour/60).':'.($hour - floor($hour / 60) * 60); 
        return $hour_txt;
    }
     private function fcn_timeOff($timesheet,$dayoffList)
    {
       $alldayoffNumber=$dayoffPreNumber=0;
               
                /// Xét nếu ngày nghỉ phép trước tháng thì cộng vào dayoffPreNumber còn không thì cộng vào alldayoffNumber . 2 param này sẽ cộng ra ngày phép đầu tháng và ngày phép cuối tháng
                     foreach ($dayoffList as $key => $value) {
                         if ($value->amID<0) {
                           $alldayoffNumber +=0.5;
                           if ($value->month>$month) {
                               $dayoffPreNumber+=0.5;
                           }
                         }
                            if ($value->pmID<0) {
                           $alldayoffNumber +=0.5;
                           if ($value->month>$month) {
                               $dayoffPreNumber+=0.5;
                           }
                         }
                     }
                $arrayTimelate=array();
                array_push($arrayTimelate, $alldayoffNumber ,$dayoffPreNumber);
    }
    private function fcn_timeLate($timesheet,$workingtime,$dayoffList,$arrayOVT,$tck_workinghours, $tblDayoff)
    {
               
                // duyệt timesheet list để tìm giờ trễ
                  
                  $arrayTimelate=array();
                  $total=0;
                  $i=0;
                    $t = Carbon::now();
                 $hour1= \Carbon\Carbon::parse($tck_workinghours->hour1);
                 $hour2= \Carbon\Carbon::parse($tck_workinghours->hour2);
                 $hour3= \Carbon\Carbon::parse($tck_workinghours->hour3);
                 $hour4= \Carbon\Carbon::parse($tck_workinghours->hour4);
                
                $ohour4= \Carbon\Carbon::parse($tck_workinghours->newDayhour);

                  foreach ($timesheet as $key => $printTime) {
                    // duyệt nếu rơi vào ngày special dayoff
                    $amDayoff=0;
                    $pmDayoff=0;
                    foreach ($tblDayoff as $key => $tblDayofff) {
                       if (  ($tblDayofff->fromDate < $printTime->date && $tblDayofff->toDate > $printTime->date ) || ($tblDayofff->fromDate == $printTime->date && $tblDayofff->fromTerm ==self::OFF_TYPE_ALL) ||  ($tblDayofff->toDate == $printTime->date && $tblDayofff->toTerm ==self::OFF_TYPE_ALL) || ($tblDayofff->fromDate == $printTime->date && 
                        $tblDayofff->toDate >= $printTime->date)
                         ) {
                        $amDayoff=1;
                        $pmDayoff=1;
                      } 
                      elseif ( ($tblDayofff->fromDate == $printTime->date && $tblDayofff->fromTerm ==self::OFF_TYPE_MOR) 
                      || ($tblDayofff->toDate == $printTime->date && $tblDayofff->toTerm ==self::OFF_TYPE_MOR) ){
                        $amDayoff=1;
                      }
                       elseif ( ($tblDayofff->fromDate == $printTime->date && $tblDayofff->fromTerm ==self::OFF_TYPE_AFT) 
                        || ($tblDayofff->toDate == $printTime->date && $tblDayofff->toTerm ==self::OFF_TYPE_AFT)) {
                         $pmDayoff=1;
                      }
                    }
                   
                    $login= \Carbon\Carbon::parse($printTime->attendance);
                    $logout=\Carbon\Carbon::parse($printTime->leaving);
                    $lateTime=0;
                    foreach ($dayoffList as $key => $dayoff) {
                      if ($printTime->date==$dayoff->date
                       && $dayoff->status==self::MAN_APP_2
                       && $dayoff->amID <>0) {
                       $amDayoff=1;
                      }
                        if ($printTime->date==$dayoff->date
                       && $dayoff->status==self::MAN_APP_2
                       && $dayoff->pmID <>0) {
                       $pmDayoff=1;
                      }
                     
                    }
                    if ( ( $amDayoff==1 && $pmDayoff==1)) {
                      array_push($arrayTimelate, 0);
                    } elseif ($amDayoff==1 ) {
                        if ($login > $hour4) {
                        $lateTime+=$this->timeLate($login , $hour4);
                    } 
                     if ($logout < $hour2) {
                        $lateTime+=$this->timeLate($hour2 , $logout);
                    } 
                     array_push($arrayTimelate, $lateTime);
                     $total+=$lateTime;
                    }elseif ($pmDayoff==1 ) {
                        if ($login > $hour1) {
                        $lateTime+=$this->timeLate($login , $hour1);
                    } 
                     if ($logout < $hour3) {
                        $lateTime+=$this->timeLate($hour3 , $logout);
                    } 
                     array_push($arrayTimelate,$lateTime);
                     $total+=$lateTime;
                    } 

                     elseif ($printTime->typeName =='WO' && $workingtime[$i]-$arrayOVT[$i] < 480 && $workingtime[$i]>0  ) {
                       array_push($arrayTimelate, 480 - ($workingtime[$i]-$arrayOVT[$i]) );
                       $total+= 480 - ($workingtime[$i]-$arrayOVT[$i]);
                    } else 
                     array_push($arrayTimelate, 0);
                     $i ++;
                }

                      
                array_push($arrayTimelate, $total);
                 


                return $arrayTimelate;
    }

private function fcn_WorkHourLST($timesheet,$tck_workinghours,$OTlist2,$dayoffList, $tblDayoff)
    {
      $arrayWorkHour=array();
      $total=0;
      foreach ($timesheet as $key => $value) {
                 $whour=0;
                 $start;
            
                 $breakVal=0;
                 $login= \Carbon\Carbon::parse($value->attendance);
                 $logout=\Carbon\Carbon::parse($value->leaving);
                 $hour1= \Carbon\Carbon::parse($tck_workinghours->hour1);
                 $hour2= \Carbon\Carbon::parse($tck_workinghours->hour2);
                 $hour3= \Carbon\Carbon::parse($tck_workinghours->hour3);
                 $hour4= \Carbon\Carbon::parse($tck_workinghours->hour4);
             foreach ($dayoffList as $key => $dayoff) {
               if ( $value->date==$dayoff->date) {
                if ($dayoff->amID <> 0 && $dayoff->pmID <> 0) {
                 $breakVal=3;
                } elseif ($dayoff->amID <> 0) {
                    $breakVal+=1;
                }elseif ($dayoff->pmID <> 0) {
                   $breakVal+=2;
                }
                 }
               }
              foreach ($tblDayoff as $key => $tblDayofff) {
                       if (  ($tblDayofff->fromDate < $value->date && $tblDayofff->toDate > $value->date ) || ($tblDayofff->fromDate == $value->date && $tblDayofff->fromTerm ==self::OFF_TYPE_ALL) ||  ($tblDayofff->toDate == $value->date && $tblDayofff->toTerm ==self::OFF_TYPE_ALL) || ($tblDayofff->fromDate == $value->date && 
                        $tblDayofff->toDate >= $value->date)
                         ) {
                   $breakVal=3;
                      } 
                      elseif ( ($tblDayofff->fromDate == $value->date && $tblDayofff->fromTerm ==self::OFF_TYPE_MOR) 
                      || ($tblDayofff->toDate == $value->date && $tblDayofff->toTerm ==self::OFF_TYPE_MOR) ){
                         $breakVal=1;
                      }
                       elseif ( ($tblDayofff->fromDate == $value->date && $tblDayofff->fromTerm ==self::OFF_TYPE_AFT) 
                        || ($tblDayofff->toDate == $value->date && $tblDayofff->toTerm ==self::OFF_TYPE_AFT)) {
                        $breakVal=2;
                      }
                    }

               // Vì row cuối cùng là tổng của tất cả các row giờ làm nên trường hợp nghỉ cả ngày phải 
               // xét lùi xuống bên dưới cùng
         
         if ($breakVal==1 && $login < $hour4) {$login = $hour4;}
        elseif ($breakVal==2 && $logout > $hour3) {$logout = $hour3;} 


                $ohour4= \Carbon\Carbon::parse($tck_workinghours->newDayhour);
                // ohour4 là 0h tính số phút từ start và endm trừ ngược để ra giờ làm việc
                // tuy nhiên nếu logout > 17h thì chỉ tính từ 17h thôi
                 $startm= 0;

                  if ($login<$hour1) {
                   $startm=Floor($hour1->diffInMinutes($ohour4) /15) *15    ; 
                 } else {
                  if ($login>$hour3 && $login < $hour4 ) {
                   $startm=Floor($hour4->diffInMinutes($ohour4) /15) *15    ; }
                  else {
                  $startm=CEIL($login->diffInMinutes($ohour4) /15) *15    ; }
                 }
                // $startm= CEIL( $login->diffInMinutes($ohour4) /15) *15    ; 
                 $endm=0;
                 if ($logout>$hour2) {
                   $endm=Floor($hour2->diffInMinutes($ohour4) /15) *15    ; 
                 } else {$endm=Floor($logout->diffInMinutes($ohour4) /15) *15    ; }
                 
                 $whour=$endm -$startm;
                 if ($logout >$hour4 && $login <$hour3) {
                 $whour-=60;
                 }
               
                  /// sum overTime
                   $timeOTSum=0;
                   // Nếu nghỉ cả ngày thì workinghouse set là 0
                   if ($breakVal==3) {  $whour = 0; }
                    // Nếu ngày cuối tuần hoặc chủ nhật set workinghour =0 
                   if($value->typeName == 'WK' || $value->typeName == 'HO' )
                      {
                        $whour = 0;
                      }
              foreach ($OTlist2 as $key => $OTvalue) {
                if ($OTvalue->usrID==$value->code && $OTvalue->date==$value->date 
                  && $OTvalue->companyCode==$value->companyCode) {
                   $startOT= \Carbon\Carbon::parse($OTvalue->start);
                   $endOT= \Carbon\Carbon::parse($OTvalue->end);
                   $timeOTSum+=$endOT->diffInMinutes($startOT);
                }
                       /// Xét trường hợp ngày lễ, ngày cuối tuần nếu có đơn làm thêm được manager B thông qua thì xét theo giá trị chọn để xét whour = oriNum * 240
                   if( $OTvalue->statusCP == '402'  && $OTvalue->date == $value->date  ) {
                     $whour= $OTvalue->oriNum * 240;
        }
      
       
        
    
        

              }

              /// end sum overTime

               if ($whour>0 ) {
                $whour+=$timeOTSum;
                
                  } else {$whour=0; $whour+=$timeOTSum;}
                   $total+=$whour;


                  array_push($arrayWorkHour, $whour);
                 
  
      }
      array_push($arrayWorkHour, $total);
      return $arrayWorkHour;

    }

    // Count workinghour (Max 8h)
private function fcn_WorkHourLST8h($timesheet,$tck_workinghours,$OTlist2,$dayoffList, $tblDayoff)
    {
      $arrayWorkHour=array();
      $total=0;
      foreach ($timesheet as $key => $value) {
                 $whour=0;
                 $start;
            
                 $breakVal=0;
                 $login= \Carbon\Carbon::parse($value->attendance);
                 $logout=\Carbon\Carbon::parse($value->leaving);
                 $hour1= \Carbon\Carbon::parse($tck_workinghours->hour1);
                 $hour2= \Carbon\Carbon::parse($tck_workinghours->hour2);
                 $hour3= \Carbon\Carbon::parse($tck_workinghours->hour3);
                 $hour4= \Carbon\Carbon::parse($tck_workinghours->hour4);
             foreach ($dayoffList as $key => $dayoff) {
               if ( $value->date==$dayoff->date) {
                if ($dayoff->amID <> 0 && $dayoff->pmID <> 0) {
                 $breakVal=3;
                } elseif ($dayoff->amID <> 0) {
                    $breakVal=1;
                }elseif ($dayoff->pmID <> 0) {
                   $breakVal=2;
                }
                 }
               }
              foreach ($tblDayoff as $key => $tblDayofff) {
                       if (  ($tblDayofff->fromDate < $value->date && $tblDayofff->toDate > $value->date ) || ($tblDayofff->fromDate == $value->date && $tblDayofff->fromTerm ==self::OFF_TYPE_ALL) ||  ($tblDayofff->toDate == $value->date && $tblDayofff->toTerm ==self::OFF_TYPE_ALL) || ($tblDayofff->fromDate == $value->date && 
                        $tblDayofff->toDate >= $value->date)
                         ) {
                   $breakVal=3;
                      } 
                      elseif ( ($tblDayofff->fromDate == $value->date && $tblDayofff->fromTerm ==self::OFF_TYPE_MOR) 
                      || ($tblDayofff->toDate == $value->date && $tblDayofff->toTerm ==self::OFF_TYPE_MOR) ){
                         $breakVal=1;
                      }
                       elseif ( ($tblDayofff->fromDate == $value->date && $tblDayofff->fromTerm ==self::OFF_TYPE_AFT) 
                        || ($tblDayofff->toDate == $value->date && $tblDayofff->toTerm ==self::OFF_TYPE_AFT)) {
                        $breakVal=2;
                      }
                    }

               // Vì row cuối cùng là tổng của tất cả các row giờ làm nên trường hợp nghỉ cả ngày phải 
               // xét lùi xuống bên dưới cùng
         
         if ($breakVal==1 && $login < $hour4) {$login = $hour4;}
        elseif ($breakVal==2 && $logout > $hour3) {$logout = $hour3;} 


                $ohour4= \Carbon\Carbon::parse($tck_workinghours->newDayhour);
                // ohour4 là 0h tính số phút từ start và endm trừ ngược để ra giờ làm việc
                // tuy nhiên nếu logout > 17h thì chỉ tính từ 17h thôi
                 $startm= 0;

                  if ($login<$hour1) {
                   $startm=Floor($hour1->diffInMinutes($ohour4) /15) *15    ; 
                 } else {
                  if ($login>$hour3 && $login < $hour4 ) {
                   $startm=Floor($hour4->diffInMinutes($ohour4) /15) *15    ; }
                  else {
                  $startm=CEIL($login->diffInMinutes($ohour4) /15) *15    ; }
                 }
                // $startm= CEIL( $login->diffInMinutes($ohour4) /15) *15    ; 
                 $endm=0;
                 if ($logout>$hour2) {
                   $endm=Floor($hour2->diffInMinutes($ohour4) /15) *15    ; 
                 } else {$endm=Floor($logout->diffInMinutes($ohour4) /15) *15    ; }
                 
                 $whour=$endm -$startm;
                 if ($logout >$hour4 && $login <$hour3) {
                 $whour-=60;
                 // Nếu login trước 12h, logout sau 13h thì trừ đi khoảng nghỉ trưa 60 phút
                 // Nếu login trước 12h logout trước 13h ví dụ 12h 30 thì cũng phải trừ đi khoản thời 
                 //gian giữa logout và 12h.

                 } elseif ($logout < $hour4 && $login <$hour3) {
                  
                 }
               
                  /// sum overTime
                   $timeOTSum=0;
                   // Nếu nghỉ cả ngày thì workinghouse set là 0
                   if ($breakVal==3) {  $whour = 0; }
                    // Nếu ngày cuối tuần hoặc chủ nhật set workinghour =0 
                   if($value->typeName == 'WK' || $value->typeName == 'HO' )
                      {
                        $whour = 0;
                      }
              foreach ($OTlist2 as $key => $OTvalue) {
           
                       /// Xét trường hợp ngày lễ, ngày cuối tuần nếu có đơn làm thêm được manager B thông qua thì xét theo giá trị chọn để xét whour = oriNum * 240
                   if( $OTvalue->statusCP == '402'  && $OTvalue->date == $value->date  ) {
                     $whour= $OTvalue->oriNum * 240;
        }
      
       
        
    
        

              }

              /// end sum overTime

               if ($whour>0 ) {
                $whour+=$timeOTSum;
                
                  } else {$whour=0; $whour+=$timeOTSum;}
                   $total+=$whour;


                  array_push($arrayWorkHour, $whour);
                 
  
      }
      array_push($arrayWorkHour, $total);
      return $arrayWorkHour;

    }
      // Count workinghour (Max 8h)
private function fcn_countDay($dayoffList,$month,$annualLeave,$minus)
    {
      
     
       $daySCPLeave=$dayUnpaidLeave=$dayAnnualStart=$dayAnnualSum=$dayAnnualEnd= 0;
       $dayAnnualStart=$dayAnnualEnd=$annualLeave-$minus;
       // $dayoffList duyệt danh sách dayoffb từ tháng month trở đi.  nếu month > monthsearch thì tính
       // là của những tháng sau đó. 
       foreach ($dayoffList as $key => $dayoffb) {
        // count Annual leave start
          if ($dayoffb->amID ==-1 ) 
             {
              $dayAnnualStart +=0.5;
               if ($dayoffb->month>$month) {
                  $dayAnnualEnd +=0.5;
               }
                if ($dayoffb->month==$month) {
                  $dayAnnualSum +=0.5;
               }
             }
           if ($dayoffb->pmID ==-1 ) 
             {
              $dayAnnualStart +=0.5;
               if ($dayoffb->month>$month) {
                  $dayAnnualEnd +=0.5;
               }
                if ($dayoffb->month==$month) {
                  $dayAnnualSum +=0.5;
               }
             }  
              // count Annual leave end + count Special leave + CP leave (= -10 or >0) start
              if ($dayoffb->month==$month  )
              { if ($dayoffb->amID ==-10 || $dayoffb->amID >0 ) {
                 $daySCPLeave+=0.5;
              }
                if ($dayoffb->pmID ==-10 || $dayoffb->pmID >0 ) {
                 $daySCPLeave+=0.5;
              }
           
              // cout day Unpaidleave =-5
                if ($dayoffb->amID ==-5){
                $dayUnpaidLeave+=0.5;
                  $dayAnnualStart +=0.5;
              }
               if ($dayoffb->pmID ==-5){
                $dayUnpaidLeave+=0.5;
                  $dayAnnualStart +=0.5;
              }
              }
              // Trường hợp xin nghỉ vào tháng sau của thời điểm search ví dụ đang là tháng 9 
              // xin nghỉ vào tháng 10 thì cộng vào start month và end month
             // count Special leave + CP leave (= -10 or >0) end
                  if ($dayoffb->month > $month  )
                    {
                      if ($dayoffb->amID ==-5  ) 
                    {
                   $dayAnnualStart +=0.5;
                    $dayAnnualEnd +=0.5;
                

                       }
                       if ($dayoffb->pmID ==-5  ) 
                    {
                   $dayAnnualStart +=0.5;
                    $dayAnnualEnd +=0.5;
                

                       }
                     }

       }
        $arrayCountDay = array(
      "daySCPLeave"=>  $daySCPLeave,
      "dayUnpaidLeave"=>  $dayUnpaidLeave,
      "dayAnnualStart"=>  $dayAnnualStart,
      "dayAnnualSum"=>  $dayAnnualSum,
      "dayAnnualEnd"=>  $dayAnnualEnd );
      return $arrayCountDay;
    }
    
    private function fcn_OverTimeLST($timesheet,$OTlist2,$tck_workinghours)
    {
       $arrayOVT=array();
       $total=0;
       $ohour1= \Carbon\Carbon::parse($tck_workinghours->ohour1);
       $ohour2= \Carbon\Carbon::parse($tck_workinghours->ohour2);
       $ohour3= \Carbon\Carbon::parse($tck_workinghours->ohour3);
       $ohour4= \Carbon\Carbon::parse($tck_workinghours->ohour4);
       $ohour5= \Carbon\Carbon::parse($tck_workinghours->ohour5);
      foreach ($timesheet as $key => $value) {
              $timeOTSum=0;
              foreach ($OTlist2 as $key => $OTvalue) {
                if ($OTvalue->usrID==$value->code && $OTvalue->date==$value->date 
                  && $OTvalue->companyCode==$value->companyCode) {
                   $startOT= \Carbon\Carbon::parse($OTvalue->start);
                   $endOT= \Carbon\Carbon::parse($OTvalue->end);
                      // Nếu thời gian làm thêm buổi tối lớn hơn mốc giờ làm đêm thì lấy end là mốc làm đêm 22h còn nếu mốc end nhỏ hơn mốc làm đêm buổi sáng thì lấy mốc sáng là 6h -->
           if($endOT > $ohour2) { $endOT = $ohour2;}
           if($endOT < $ohour5) { $endOT = $ohour5;}
                   $timeOTSum+=$endOT->diffInMinutes($startOT);
                }
              }
              $total+=$timeOTSum;
              array_push($arrayOVT, $timeOTSum);
            }
               array_push($arrayOVT,  $total);
          return $arrayOVT;     
    }
     public function downloadPDF($id){
      $user = UserDetail::find($id);

      $pdf = PDF::loadView('pdf', compact('user'));
      return $pdf->download('invoice.pdf');

    }

}
