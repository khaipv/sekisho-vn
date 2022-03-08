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
use App\Tck_print;
use App\Tck_workinghours;
use App\Library\CommonTimeFunction;
use DB;
use App\User;
use App\Tck_overtime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UDPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
      $t = Carbon::now();
         $days=null ;
       // $days = DB::select($sql);
        return view('udp.udp', compact('master','userH','days'));
        
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

     private function getOTDay($from,$to,$code)
    {
           $days = DB::table('tck_dayyear as dy')
              ->join('tck_print as print','print.date','=','dy.date')
              ->leftjoin('tck_master','print.status','=','tck_master.val')  
              ->leftjoin('tck_workinghours as wh', 'wt_code','=','wh.id')
              -> whereBetween('dy.date',[$from, $to])
              ->where('print.code','=',$code)
           ->selectRaw('dy.*, DATE_FORMAT(attendance, "%H:%i") attendance   , 
            DATE_FORMAT(leaving, "%H:%i") leaving ,tck_master.name as status,print.id as id,print.note,print.inEdit,print.outEdit,print.mannote1,print.mannote2
            ,subtime(wh.hour1,attendance) as latetime,wh.hour1 - attendance as late')
           ->orderBy('date','ASC')
           ->get();
           return $days;
    }
    public function otDaySearch(Request $request)
    {
      $request->flash();
      $userH = $this->getUsr();
      $days=$this->getOTDay($request->fromDates,$request->toDates,$userH->code);
      return view('udp.udp', compact('userH','days'));

    }
     private function getUsr()
    {
             $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
      $userH = DB::table('tck_user')
                  ->leftjoin('users', 'tck_user.code','=','users.code')
                  ->leftjoin('tck_user as ma', 'tck_user.ma','=','ma.id')
                  ->leftjoin('tck_user as mb', 'tck_user.mb','=','mb.id')
                   ->select('tck_user.*','ma.name as maName','mb.name as mbName')
                    ->where("users.id","=",  $user->id)
                    ->first();
                    return $userH;

    }
     public function updatePrint(Request $request)
    {
      try {
          $userH=$this->getUsr();
      $dateArr=$fromTime=$toTime=$note=array();
      $dateArr=$request->date;
      $fromTime=$request->fromTime;
      $toTime=$request->toTime;
      $note=$request->note;     
    //  dd(sizeof($dateArr));

      
         
       //  duyệt foreach dateArr update theo date and code.
     for ($i=0; $i <sizeof($dateArr) ; $i++)  {
          $ori_print=DB::table('tck_print') 
            -> where('code','=',$userH->code)
            ->where('date','=',$dateArr[$i]) 
            ->select('tck_print.*')
           ->first();
             $fromTimeC =  Carbon::parse($fromTime[$i]);
             $toTimeC   =  Carbon::parse($toTime[$i]);
             $dbFrom    =  Carbon::parse($ori_print->attendance);
             $dbto      =  Carbon::parse($ori_print->leaving);
             $dbinEdit  =  Carbon::parse($ori_print->inEdit);
             $dboutEdit  =  Carbon::parse($ori_print->outEdit);
           if( ($fromTimeC->diffInMinutes($dbFrom) >0
            || $toTimeC->diffInMinutes($dbto) >0 ) 
            || (  ! is_null($ori_print->inEdit) 
              &&  $fromTimeC->diffInMinutes($dbinEdit) >0 )
            || (!is_null($ori_print->outEdit) && 
              $toTimeC->diffInMinutes($dboutEdit) >0 ) )
           {
          $item=Tck_print::updateOrCreate(
              [
              'code'=>$userH->code,
              'date'=>$dateArr[$i],
              ],
                           [
                            'inEdit'=>  $fromTime[$i],
                            'outEdit'=>  $toTime[$i],
                            'note'=>$note[$i] ,   
                            'status'=>107,                      
                           ]);
                          $item->save();
     
       }  //end if data changed
       // end for
      }
      } catch (Exception $e) {
        
      }
    
      return back();
    }
}
