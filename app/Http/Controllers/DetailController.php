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
class DetailController extends Controller
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
    public function index()
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
    }
    public function appDetail($id,$type)
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

          ->leftjoin('tck_user as ma','ma.id','=','tck_user.ma')
          ->leftjoin('tck_user as mb','mb.id','=','tck_user.mb')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest,ma.name as maName,mb.name as mbName')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();
     
         
      // select union day
                    
                    $t = Carbon::now();
     $dayAlls =  DB::table('tck_overtime as ot')
       ->leftjoin('tck_master as typetbl', 'ot.statusCP','=','typetbl.val')
       ->leftjoin('tck_master as statustbl', 'ot.status','=','statustbl.val')
       ->leftjoin('tck_master as termtbl', 'ot.term','=','termtbl.val')
       ->where('ot.usrID','=',$userH->code)
       ->whereRaw("year(ot.date)=".  $t->year)
       ->whereRaw("month(ot.date)=".  $t->month)
       ->where('statusCP','=','402')
      ->select(['ot.oriNum as oriNum','ot.datenum as datenum','ot.date as date','termtbl.val as term','typetbl.name as mtype','statustbl.name as mstatus','note',DB::raw('null as toDate'),DB::raw('null as mtermFrom '),DB::raw('null as mtermTo')]) ->orderBy('date','ASC') ->get();
        
      $deleteObjOVT;$deleteDayoff;
       if ($type =="401" || $type=="402"|| $type=="405") {
          $deleteObjOVT = DB::table('tck_overtime')
          ->where("tck_overtime.id","=", $id)->first();
           $days = DB::table('tck_dayyear as dy')
          ->leftjoin('tck_print as pr', 'pr.date', '=',
           DB::raw('dy.date and pr.code='.$userH->code))
          ->where("dy.date","=", $deleteObjOVT->date)
          ->selectRaw('dy.*,pr.attendance,pr.leaving')
         ->first();
      $rest='0';
       // Tính ra thời gian nghỉ rest nếu attendance,leaving nằm giữa rest time thì set rest=1:00
      if ($days->attendance< $userH->restE && $days->leaving< $userH->restE) {
         $rest=$userH->rest;
      }

       } else {
        $deleteDayoff= DB::table('tck_dayoff')
        ->where('id','=',$id)->first();

       }
       

     
       return view('overtime.detailHO', compact('master','userH','days','rest','masterTerm','masterFrom','masterTo','dayAlls','deleteObjOVT','deleteDayoff','type'));
    }
    

}