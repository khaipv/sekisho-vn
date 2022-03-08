<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;
use App\User;
use App\Tck_master;
use Illuminate\Support\Facades\Auth;
use App\Tck_workinghours;
use App\tck_overtime;
use App\Tck_print;
use App\Tck_user;
use App\Tck_dayyear;

class TimeStaffController extends Controller
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
    const MAN_DENY_2=104;
    const MAN_APP_1=105;
    const MAN_APP_2=106;
    const AP_UDP_1=107;
    public function index()
    {

        $request = new \Illuminate\Http\Request();
         
          $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }

         $users= $this->getUserlist($user);
         $from=$request->input('fromDates');
        
         $to=$request->input('toDates');
          $lateE=0;
         $normal_OT=$normal_Midnight=$weekend_WK=$weekend_OT=$weekend_Midnight=$HO_WK=$HO_OT
         =$HO_Midnight =0;

            
             
      
        $master = Tck_master::sortable()
            ->orderBy('id', 'desc')
            ->sortable()
            ->paginate(10);
        $sID= $request->get('tck_user');
        $userH = DB::table('tck_user')
                  ->leftjoin('users', 'tck_user.id','=','users.id')
                   ->select('users.*','tck_user.*')
                    ->where("tck_user.id","=",  $sID)
                    ->first();
            $userM = DB::table('tck_user')
                  ->leftjoin('users', 'tck_user.id','=','users.id')
                   ->select('users.*','tck_user.*')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();           
        $tck_user=DB::table('tck_user')
                 ->selectRaw("tck_user.id,CONCAT(tck_user.name,'-',tck_user.code) as name,tck_user.code") 
                 ->get();
       $queryI = Tck_print::query();  //Initializes the query
                 $queryI ->leftjoin('tck_workinghours as wh', 'wt_code','=','wh.id')
                 ->leftjoin('tck_dayyear as y', 'tck_print.date','=','y.date')
                 ->leftjoin('tck_user as us', 'tck_print.code','=','us.code')
                    ->select(
                        'tck_print.date','tck_print.id','tck_print.code','us.name','y.nameX',
                        'attendance','leaving','workingtime','wh.breaks as breaks'
                       
                        ,'y.typeName','y.name as typedate'
                        ,'wh.hour1 as hour1'
                        ,'wh.hour2 as hour2'
                        ,'tck_print.mannote1'
                        ,'tck_print.mannote2'
                         ,'tck_print.note'
                    )
                    ->where('tck_print.status','=',self::AP_UDP_1);
                    if (!is_null($userH)) {


                         $queryI->where("tck_print.code","=",$userH->code);
                     $t = Carbon::now();
                      if (is_null($from) && is_null($to)) {

                               $queryI ->whereRaw("year(tck_print.date)=".  $t->year)
                               ->whereRaw("month(tck_print.date)=".  $t->month-1);
                             } elseif (!is_null($from)) {
                                $queryI ->where('tck_print.date','>=',$from);
                             } 
                             if (!is_null($to)) {
                                $queryI ->where('tck_print.date','<=',$to);
                             }
                     //  count holiday
                    $queryHoliday = tck_dayyear::query(); 
                    $queryHoliday->select('typeName');

                             
                     // count Over time... 1 get OT list
                    $queryOT = tck_overtime::query();  //Initializes the query
                    $queryOT ->join('tck_dayyear as yr', 'tck_overtime.date','=','yr.date')
                             ->join('tck_user as us','us.code','=','tck_overtime.usrID') 
                             ->leftjoin('tck_rankot', 'tck_rankot.typeName','=','yr.typeName')
                             ->select('tck_overtime.date','tck_overtime.start','tck_overtime.end','yr.typeName','tck_overtime.term','tck_overtime.statusCP','tck_rankot.val as otval,us.code')
                             ->where("us.code","=",$userH->code)
                             ->where("tck_overtime.status","=",self::MAN_APP_2);
                     if (is_null($from) && is_null($to)) {
                               $queryOT ->whereRaw("year(yr.date)=".  $t->year)
                               ->whereRaw("month(yr.date)=".  $t->month);
                                $queryHoliday->whereRaw("year(date)=".  $t->year)
                               ->whereRaw("month(date)=".  $t->month);
                             } if (!is_null($from)) {
                                $queryOT ->where('yr.date','>=',$from);
                                $queryHoliday->where('date','>=',$from);
                             } if (!is_null($to)) {
                                $queryOT ->where('yr.date','<=',$to);
                                $queryHoliday ->where('date','<=',$to);
                             }
                      // getworking time from userH
                      $tck_workinghours=Tck_workinghours::find($userH->whID);    
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
                                $weekend_WK+=( $value->oriNum/$value->otval )*4;
                                
                            }
                               if ($value->typeName=='HO') {
                                $HO_WK+=( $value->oriNum/$value->otval )*4;
                                
                            }
                            // end if OT-Compensation Date

                            
                        }

                        // end count OVTime, wk OT, HO OT
                         }   
                      //end working hour1      
                     }
                     $queryHoliday->where('typeName','=','HO')
                     ->get();


         

           }  //end if exist userH


                      $timesheet=$queryI->orderBy('date', 'ASC')->paginate(50);
                      $workingtimes =null;
                     if (!is_null($userH)) {
                               $workingtimes = DB::table('tck_print')
                                 ->where("code","=",  $userH->code)
                                 ->select(DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC( workingtime))) as workingTime'))->first();

                     } 
     
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
        return view ('timestaff.index',compact('timesheet','tck_user','userH','userM'));
    }

    /**
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
          $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
        $acceptArr=$idArr=$noteArr=$denyArr=array();
        $acceptArr=$request->accept;
        $denyArr=$request->deny;
        $noteArr=$request->note;
        $idArr=$request->timeSID;
        
        $i=0;

        foreach ($idArr as $key => $idList) {
           $printUDP=Tck_print::findOrFail($idList);
           if (!is_null($printUDP)) {
            
            // duyệt array accept
              if (!is_null($acceptArr)&& in_array($idList,$acceptArr)&& ($printUDP->accept <> $idList )) {
                $tck_staff= DB::table('tck_user')
                    ->where("tck_user.code","=",  $printUDP->code)
                    ->first();

                 if ($tck_staff->ma==$user->id) {
                    
                    $printUDP->mannote1=$noteArr[$i];
                     $printUDP->accept=$idList;
                      $printUDP->status=self::MAN_APP_1;
                 }
                  if ($tck_staff->mb==$user->id) {

                    $printUDP->mannote2=$noteArr[$i];
                     $printUDP->accept=$idList; 
                     $printUDP->status=self::MAN_APP_2;
                 }

                 $printUDP->save();
                }
                 // duyệt array deny
               // dd( $denyArr);

             if ( !is_null($denyArr)&& in_array($idList,$denyArr)&& ($printUDP->deny <>$idList )) {
                $tck_staff= DB::table('tck_user')
                    ->where("tck_user.code","=",  $printUDP->code)
                    ->first();

                 if ($tck_staff->ma==$user->id) {
                    $printUDP->mannote1=$noteArr[$i];
                     $printUDP->deny=$idList;
                      $printUDP->status=self::MAN_DENY_1;
                 }
                 if ($tck_staff->mb==$user->id) {
                    $printUDP->mannote1=$noteArr[$i];
                     $printUDP->deny=$idList;
                      $printUDP->status=self::MAN_DENY_2;
                 }
                  $printUDP->save();
                }
              }
              $i++;
           }
           return back();
        
        
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
        DB::connection()->enableQueryLog();
         $user = Auth::user();
               if (is_null($user) ) {
                   return  redirect('/');
                  }
                 $users= $this->getUserlist($user);
                 $from=$request->input('fromDates');
                 $to=$request->input('toDates');
         $userSCode= $request->get('tck_user');
          $request->flash();

               // delete

             $tck_user=DB::table('tck_user')
                         ->selectRaw("tck_user.id,CONCAT(tck_user.name,'-',tck_user.code) as name,tck_user.code") 
                         ->get();

              $userM = DB::table('tck_user')
                  ->leftjoin('users', 'tck_user.id','=','users.id')
                   ->select('users.*','tck_user.*')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();                       
                         
            $queryI = Tck_print::query();   //Initializes the query
                         $queryI ->leftjoin('tck_workinghours as wh', 'wt_code','=','wh.id')
                         ->leftjoin('tck_dayyear as y', 'tck_print.date','=','y.date')
                         ->leftjoin('tck_user as us', 'tck_print.code','=','us.code')
                            ->select(
                                'tck_print.date','tck_print.id','tck_print.code','us.name','y.nameX',
                                'attendance','leaving','workingtime','wh.breaks as breaks'
                               
                                ,'y.typeName','y.name as typedate'
                                ,'wh.hour1 as hour1'
                                ,'wh.hour2 as hour2'
                                ,'tck_print.accept'
                                ,'tck_print.deny'
                                ,'tck_print.id'
                                ,'tck_print.mannote1'
                                ,'tck_print.mannote2'
                                ,'tck_print.note'
                            ) ->where('tck_print.status','=',self::AP_UDP_1);
                            // search condition
             if (!is_null($userSCode)) {
                                 $queryI->where("tck_print.code","=",$userSCode);
                                   }
             $t = Carbon::now();
                             if (!is_null($from)) {
                                        $queryI ->where('tck_print.date','>=',$from);
                                     } 
                                     if (!is_null($to)) {
                                        $queryI ->where('tck_print.date','<=',$to);
                                     }
               // $queries = DB::getQueryLog();
               // $lastQuery = end($queries);
               //    dd($lastQuery);
              $timesheet=$queryI->orderBy('date', 'ASC')->paginate(50);
              
               return view ('timeapp.index',compact('timesheet','tck_user','userH','userM'));
                            
                            
                    
                    

 
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
        return ceil( $to1->diffInMinutes($from1)/4)*4;
    }
}
