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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Session;
use Illuminate\Support\Facades\Redirect;
class ApplicationController extends Controller
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
    const OT_DELETE_CP=404;

         const MAN_DENY_1=103;
    const MAN_DENY_2=104;
    public function index()
    {
      Session::flash('backUrl', URL::full());
        $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
      $firstDay = Carbon::now()->startOfMonth()->toDateString();
      $lastDay ;
      $masters=Tck_master::all();
      $mastersType=Tck_master::where('type','=','app') ->orWhere('type','=','dayoff')->get();
      $userH = DB::table('tck_user')
                    ->join('users', 'tck_user.code','=','users.code')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest, users.role as role')
                    ->where("tck_user.code","=",  $user->code)
                    ->where("tck_user.year","=",  Carbon::now()->year)
                    ->first();
      $today = Carbon::now();
      $staffLst=DB::table('tck_user as us')
                ->where('companyCode','=',$userH->companyCode)
                ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
                ->get();

      // CREATE man_list : nếu user đăng nhập là mannager 1 (ma=0,mb=2) thì search ra các 
      // status= created(101 ) Nếu user là manager 2 (ma=0,mb=0) thì search ra các status đã được /
    //approve bởi man1 (105)            
           $man_list='(101)';
           if ($userH->ma==0 && $userH->mb==0) {
                 $man_list=' (101,105) ';
                } elseif ($userH->ma==0 && $userH->mb <> 0) {
                 $man_list=' (101) ';
                }    

     // end man_list           
      // select union day
      $master="";
      $dayOTs = DB::table('tck_overtime as ot')
       ->join('tck_user as us','us.code','=','ot.usrID')
       ->leftjoin('tck_master as typetbl', 'ot.statusCP','=','typetbl.val')
       ->leftjoin('tck_master as statustbl', 'ot.status','=','statustbl.val')
       ->leftjoin('tck_master as termtbl', 'ot.term','=','termtbl.val')
       //->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
             ->whereRaw(' ( ot.status  in '.$man_list.' )')
       ->where('us.companyCode','=',$userH->companyCode)
        ->where('ot.usrID','=',$userH->code)
        ->whereRaw(' month( ot.date)>= '.Carbon::now()->month)
       ->select(["ot.statusCP as typedb",'ot.id as id','ot.date as date','typetbl.name as mtype','statustbl.name as mstatus','note',DB::raw('null as toDate'),DB::raw('termtbl.name as mtermFrom '),DB::raw('null as mtermTo '),'ot.status as statusCode','us.code as usCode','us.name as usName','ot.created_at as created','ot.mannote1','ot.mannote2',DB::raw(" concat('ot',ot.id) as idType " 
        ),'datenum','oriNum']);
       
       $dayOffs = DB::table('tck_dayoff as off')
       ->join('tck_user as us','us.code','=','off.usrCode')
        ->leftjoin('tck_master as statustbl', 'off.status','=','statustbl.val') 
        ->leftjoin('tck_master as typetbl', 'off.type','=','typetbl.val')   
        ->leftjoin('tck_master as termFromtbl', 'off.fromterm','=','termFromtbl.val')
        ->leftjoin('tck_master as termTotbl', 'off.toterm','=','termTotbl.val')
        ->whereRaw('( us.code ='.$userH->code.' )')
       
         ->whereRaw(' ( off.status  in '.$man_list.' )')
          

        ->whereRaw(' MONTH(off.fromDate) >= '.Carbon::now()->month)
        ->where('us.companyCode','=',$userH->companyCode)
        ->select(["off.type as typedb",'off.id as id','off.fromDate as date', DB::raw('typetbl.name as mtype'),'statustbl.name as mstatus','note','off.todate as toDate','termFromtbl.name as mtermFrom','termTotbl.name as mtermTo ','off.status as statusCode','us.code as usCode','us.name as usName','off.created_at as created','off.mannote1','off.mannote2' ,DB::raw(" concat('of',off.id)  as idType "),DB::raw('1 as datenum '),DB::raw('1 as oriNum ') ]);

        $dayAlls=$dayOTs ->union($dayOffs)->orderBy('usCode','date')->get();  

         $idlist=$this->fcn_ArrToString($dayAlls);
          $typedblist=$this->fcn_ArrTypeToString($dayAlls);
              $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssApprove', $links);

         return view('application.application', compact('masters','master','userH','dayAlls','mastersType','today','firstDay','lastDay',
         'idlist','typedblist'));
    }
    public function approveSearch(Request $request)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
       //   dd(URL::full());
          Session::flash('backUrl', URL::full());
      $masters=Tck_master::all();
      $mastersType=Tck_master::where('type','=','app') ->orWhere('type','=','dayoff')->get();
      $userH = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.id','=','users.id')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest,users.role')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();
      $t = Carbon::now();
      $staffLst=DB::table('tck_user as us')
                ->where('companyCode','=',$userH->companyCode)
                ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
                ->get();

      $statusSelect=$request->input('statusSelect');
      $typeSelect=$request->input('typeSelect');
      $staffSelect=$request->input('staffSelect');
      $from=$request->input('fromDate');
      $to=$request->input('toDate');
      // select union day  $queryI = Candidates::query()->sortable(); 
    $dayOTs  = tck_overtime::query()->sortable(); 
       $dayOTs ->join('tck_user as us','us.code','=','tck_overtime.usrID')
       ->leftjoin('tck_master as typetbl', 'tck_overtime.statusCP','=','typetbl.val')
       ->leftjoin('tck_master as statustbl', 'tck_overtime.status','=','statustbl.val')
       ->leftjoin('tck_master as termtbl', 'tck_overtime.term','=','termtbl.val')
       ->select(["tck_overtime.statusCP as typedb",'tck_overtime.id as id','tck_overtime.date as date','typetbl.name as mtype','statustbl.name as mstatus','note',DB::raw('null as toDate'),DB::raw('termtbl.name as mtermFrom '),DB::raw('null as mtermTo '),'tck_overtime.status as statusCode','us.code as usCode','us.name as usName','tck_overtime.created_at as created',DB::raw(" concat('ot',tck_overtime.id) as idType             "),'tck_overtime.mannote1','tck_overtime.mannote2'])
     ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
     ->where('us.companyCode','=',$userH->companyCode);
     if( !is_null( $statusSelect))
        {
          if ($statusSelect==99) {
             $dayOTs ->where('tck_overtime.status','<>',self::MAN_DENY_2)
                ->where('tck_overtime.status','<>',self::MAN_APP_2)
                ->where('tck_overtime.status','<>',self::OT_DELETE_CP);
                
          } else {

          $dayOTs->where(function ($query)use ($statusSelect) {
                  $query->Where(DB::raw("tck_overtime.status"), '=', $statusSelect);
              }); 
          }
    } else {$dayOTs ->where('tck_overtime.status','<>',self::OT_DELETE_CP);}
     if( !is_null( $typeSelect))
     {
       { $dayOTs->where(function ($query)use ($typeSelect) {
                  $query->Where(DB::raw("tck_overtime.statusCP"), '=', $typeSelect);
              }); 
     }
     } 
    if( !is_null( $staffSelect))
     {
       { $dayOTs->where(function ($query)use ($staffSelect) {
                  $query->Where(DB::raw("tck_overtime.usrID"), '=', $staffSelect);
              }); 
     }
     } 
       if (!is_null($from)) {
          $dayOTs ->where(function ($query)use ($from) {
           $query
                ->Where ('tck_overtime.date','>=', $from );
              });
        }
           if (!is_null($to)) {
          $dayOTs ->where(function ($query)use ($to) {
           $query
                ->Where ('tck_overtime.date','<=', $to );
              });
        }

        $dayOffs = tck_dayoff::query()->sortable(); 
        $dayOffs->join('tck_user as us','us.code','=','tck_dayoff.usrCode')
        ->leftjoin('tck_master as statustbl', 'tck_dayoff.status','=','statustbl.val') 
        ->leftjoin('tck_master as typetbl', 'tck_dayoff.type','=','typetbl.val')   
        ->leftjoin('tck_master as termFromtbl', 'tck_dayoff.fromterm','=','termFromtbl.val')
        ->leftjoin('tck_master as termTotbl', 'tck_dayoff.toterm','=','termTotbl.val')
        ->select(["tck_dayoff.type as typedb",'tck_dayoff.id as id','tck_dayoff.fromDate as date', DB::raw('typetbl.name as mtype'),'statustbl.name as mstatus','note','tck_dayoff.todate as toDate','termFromtbl.name as mtermFrom','termTotbl.name as mtermTo ','tck_dayoff.status as statusCode','us.code as usCode','us.name as usName','tck_dayoff.created_at as created',DB::raw(" concat('of',tck_dayoff.id)  as idType   ") ,'tck_dayoff.mannote1','tck_dayoff.mannote2'])
        ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
        ->where('us.companyCode','=',$userH->companyCode);
      if( !is_null( $statusSelect))
        {
           if ($statusSelect==99) {
             $dayOffs ->where('tck_dayoff.status','<>',self::MAN_DENY_2)
                ->where('tck_dayoff.status','<>',self::MAN_APP_2)
                   ->where('tck_dayoff.status','<>',self::OT_DELETE_CP);
          } else {

          $dayOffs->where(function ($query)use ($statusSelect) {
                  $query->Where(DB::raw("tck_dayoff.status"), '=', $statusSelect);
              });
              } 
    } else {$dayOffs ->where('tck_dayoff.status','<>',self::OT_DELETE_CP);}
     if( !is_null( $typeSelect))
     { 
      if ($typeSelect=='403') {
       { $dayOffs->where(function ($query)use ($typeSelect) {
                  $query->Where(DB::raw("tck_dayoff.type"), '=', '202'); }); 
     }
     } 
     else {
       $dayOffs->where(function ($query)use ($typeSelect) {
                  $query->Where(DB::raw("tck_dayoff.type"), '=', $typeSelect ); });  
     }  
    }
        if( !is_null( $staffSelect))
     {
       { $dayOffs->where(function ($query)use ($staffSelect) {
                  $query->Where(DB::raw("tck_dayoff.usrCode"), '=', $staffSelect);
              }); 
     }
     }
      if (!is_null($from)) {
          $dayOffs ->where(function ($query)use ($from) {
           $query
                ->Where ('tck_dayoff.fromDate','>=', $from );
              });
        }
         if (!is_null($to)) {
          $dayOffs ->where(function ($query)use ($to) {
           $query
                ->Where ('tck_dayoff.fromDate','<=', $to );
              });
        }
        $request->flash();
        $dayAlls=$dayOTs ->union($dayOffs)->orderBy('usCode','ASC') 
        ->orderBy('date','ASC') 
        ->get();  
        // sESION
       $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssApprove', $links);

            $idlist=$this->fcn_ArrToString($dayAlls);
            $typedblist=$this->fcn_ArrTypeToString($dayAlls);
           
         return view('application.application', compact('masters','master','userH','dayAlls'
          ,'mastersType','staffLst','idlist','typedblist'));
      
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
     $data=DB::table('tck_dayoffb as b')
          ->leftjoin('tck_dayoff as do','do.id','=','b.doID') 
          ->leftjoin('tck_overtime as amDB','b.amID','=','amDB.id') 
          ->leftjoin('tck_overtime as pmDB','b.pmID','=','pmDB.id')  
          ->leftjoin('tck_master as statustbl','do.status','=','statustbl.val')  
          ->where('do.id','=',$id)
          ->select('b.date','statustbl.name as status','amDB.date as amDB','pmDB.date as pmDB')
          ->orderBy('date')
          ->get();
         return view('overtime.detail', compact('data'));
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
     * @param  int  $id  request
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
       public function deleteOT($id,$type)
    {
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
          CommonTimeFunction:: deleteDayoff($id,$type);
         
       return $this->index();
    }
    public function editApp($id,$type)
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
       

     
       return view('application.editOvertime', compact('master','userH','days','rest','masterTerm','masterFrom','masterTo','dayAlls','deleteObjOVT','deleteDayoff','type'));


    }
    public function editOT(Request $request)
    {
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }          
            $auUser=Tck_user::findOrFail($user->id);
     $id=$request->objID;
     $type=$request->typeID;
     session_start();
     
     if( $_POST){
    // user double submitted 
     
     if ($type=='202'||$type=='201'||$type=='203'||$type=='204') { 
      $this->validateDayoff($request);
      CommonTimeFunction::deleteDayoff($id,$type);
      CommonTimeFunction::createDayOff($request);       
     } elseif ($type=='401') {
       $this->validateEditOT($request->get('fromOT'),$request->get('toOT'),$request, $auUser->code);
       CommonTimeFunction::deleteDayoff($id,$type);
       CommonTimeFunction::createOT($request);
        
     }
     elseif ($type=='405') {
       CommonTimeFunction::deleteDayoff($id,$type);
       CommonTimeFunction::createLeave($request);
     }
      else {
         CommonTimeFunction::deleteDayoff($id,$type);
     CommonTimeFunction::createHO($request);
     }
       }
      return  redirect('application')->with('message', 'Succes! Request was update') ;
    }

    public function appSearch(Request $request)
    {
     
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
       //   dd(URL::full());
          Session::flash('backUrl', URL::full());
      $masters=Tck_master::all();
      $mastersType=Tck_master::where('type','=','app') ->orWhere('type','=','dayoff')->get();
      $userH = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.code','=','users.code')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest,users.role')
                    ->where("tck_user.code","=",  $user->code)
                    ->first();
      $t = Carbon::now();

      $statusSelect=$request->input('statusSelect');
      $typeSelect=$request->input('typeSelect');
      $staffSelect=$request->input('staffSelect');
      $from=$request->input('fromDate');
      $to=$request->input('toDate');
      // select union day  $queryI = Candidates::query()->sortable(); 
    $dayOTs  = tck_overtime::query()->sortable(); 
       $dayOTs ->join('tck_user as us','us.code','=','tck_overtime.usrID')
       ->leftjoin('tck_master as typetbl', 'tck_overtime.statusCP','=','typetbl.val')
       ->leftjoin('tck_master as statustbl', 'tck_overtime.status','=','statustbl.val')
       ->leftjoin('tck_master as termtbl', 'tck_overtime.term','=','termtbl.val')
       ->select(["tck_overtime.statusCP as typedb",'tck_overtime.id as id','tck_overtime.date as date','typetbl.name as mtype','statustbl.name as mstatus','note',DB::raw('null as toDate'),DB::raw('termtbl.name as mtermFrom '),DB::raw('null as mtermTo '),'tck_overtime.status as statusCode','us.code as usCode','us.name as usName','tck_overtime.created_at as created',DB::raw(" concat('ot',tck_overtime.id) as idType             "),'tck_overtime.mannote1','tck_overtime.mannote2','datenum','oriNum' ])
    // ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
     ->where('us.code','=',$userH->code);
     if( !is_null( $statusSelect))
        {
          if ($statusSelect==99) {
             $dayOTs ->where('tck_overtime.status','<>',self::MAN_DENY_2)
                ->where('tck_overtime.status','<>',self::MAN_APP_2)
                ->where('tck_overtime.status','<>',self::OT_DELETE_CP);
                
          } else {

          $dayOTs->where(function ($query)use ($statusSelect) {
                  $query->Where(DB::raw("tck_overtime.status"), '=', $statusSelect);
              }); 
          }
    } else {$dayOTs ->where('tck_overtime.status','<>',self::OT_DELETE_CP);}
     if( !is_null( $typeSelect))
     {
       { $dayOTs->where(function ($query)use ($typeSelect) {
                  $query->Where(DB::raw("tck_overtime.statusCP"), '=', $typeSelect);
              }); 
     }
     } 
    if( !is_null( $staffSelect))
     {
       { $dayOTs->where(function ($query)use ($staffSelect) {
                  $query->Where(DB::raw("tck_overtime.usrID"), '=', $staffSelect);
              }); 
     }
     } 
       if (!is_null($from)) {
          $dayOTs ->where(function ($query)use ($from) {
           $query
                ->Where ('tck_overtime.date','>=', $from );
              });
        }
           if (!is_null($to)) {
          $dayOTs ->where(function ($query)use ($to) {
           $query
                ->Where ('tck_overtime.date','<=', $to );
              });
        }

        $dayOffs = tck_dayoff::query()->sortable(); 
        $dayOffs->join('tck_user as us','us.code','=','tck_dayoff.usrCode')
        ->leftjoin('tck_master as statustbl', 'tck_dayoff.status','=','statustbl.val') 
        ->leftjoin('tck_master as typetbl', 'tck_dayoff.type','=','typetbl.val')   
        ->leftjoin('tck_master as termFromtbl', 'tck_dayoff.fromterm','=','termFromtbl.val')
        ->leftjoin('tck_master as termTotbl', 'tck_dayoff.toterm','=','termTotbl.val')
        ->select(["tck_dayoff.type as typedb",'tck_dayoff.id as id','tck_dayoff.fromDate as date', DB::raw('typetbl.name as mtype'),'statustbl.name as mstatus','note','tck_dayoff.todate as toDate','termFromtbl.name as mtermFrom','termTotbl.name as mtermTo ','tck_dayoff.status as statusCode','us.code as usCode','us.name as usName','tck_dayoff.created_at as created',DB::raw(" concat('of',tck_dayoff.id)  as idType   ") ,'tck_dayoff.mannote1','tck_dayoff.mannote2',DB::raw('1 as datenum '),DB::raw('1 as oriNum ')])
       // ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
        ->where('us.code','=',$userH->code);
      if( !is_null( $statusSelect))
        {
           if ($statusSelect==99) {
             $dayOffs ->where('tck_dayoff.status','<>',self::MAN_DENY_2)
                ->where('tck_dayoff.status','<>',self::MAN_APP_2)
                   ->where('tck_dayoff.status','<>',self::OT_DELETE_CP);
          } else {

          $dayOffs->where(function ($query)use ($statusSelect) {
                  $query->Where(DB::raw("tck_dayoff.status"), '=', $statusSelect);
              });
              } 
    } else {$dayOffs ->where('tck_dayoff.status','<>',self::OT_DELETE_CP);}
     if( !is_null( $typeSelect))
     { 
      if ($typeSelect=='403') {
       { $dayOffs->where(function ($query)use ($typeSelect) {
                  $query->Where(DB::raw("tck_dayoff.type"), '=', '202'); }); 
     }
     } 
     else {
       $dayOffs->where(function ($query)use ($typeSelect) {
                  $query->Where(DB::raw("tck_dayoff.type"), '=', $typeSelect ); });  
     }  
    }
        if( !is_null( $staffSelect))
     {
       { $dayOffs->where(function ($query)use ($staffSelect) {
                  $query->Where(DB::raw("tck_dayoff.usrCode"), '=', $staffSelect);
              }); 
     }
     }
      if (!is_null($from)) {
          $dayOffs ->where(function ($query)use ($from) {
           $query
                ->Where ('tck_dayoff.fromDate','>=', $from );
              });
        }
         if (!is_null($to)) {
          $dayOffs ->where(function ($query)use ($to) {
           $query
                ->Where ('tck_dayoff.fromDate','<=', $to );
              });
        }
        $request->flash();
        $dayAlls=$dayOTs ->union($dayOffs)->orderBy('usCode','ASC') 
        ->orderBy('date','ASC') 
        ->get();  
        // sESION
       $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssApprove', $links);

            $idlist=$this->fcn_ArrToString($dayAlls);
            $typedblist=$this->fcn_ArrTypeToString($dayAlls);
           // dd($dayAlls);
         return view('application.application', compact('masters','master','userH','dayAlls'
          ,'mastersType','idlist','typedblist'));
       

      
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
      //OT_CR_STATUS
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
          $this->validateOT($request->get('fromOT'),$request->get('toOT'));
          $auUser=Tck_user::findOrFail($user->id);
          $overtime= new Tck_overtime
     ([
         'usrID'=> $auUser->code,
         'date' => $request->get('dateOT'),
         'start'=>$request->get('fromOT'),
         'end' => $request->get('toOT'),
         'companyCode'=>$auUser->companyCode,
        // 'term'=>self::OT_CR_CODE,
         'note' => $request->get('note'),
         'status'=>self::OT_CR_STATUS,
         'statusCP'=>self::OT_CR_CODE,
                ]);
        $overtime->save();
          return back();
    }
      public function createHO(Request $request)
    {
      //OT_CR_STATUS
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         // $this->validateOT($request);
          $auUser=Tck_user::findOrFail($user->id);
          $dayYear= DB::table('tck_dayyear as dy')
                    ->leftjoin('tck_rankot', 'tck_rankot.typeName','=','dy.typeName')
                    ->where('dy.date','=',$request->get('dateHO'))
                    ->selectRaw('dy.date,tck_rankot.val')
                    ->first();

          $days=0;
          if($request->get('term')==503){
            $days=$dayYear->val;
          } else $days=$dayYear->val/2;

          $overtime= new Tck_overtime
     ([
         'usrID'=> $auUser->code,
         'date' => $request->get('dateHO'),
         'term'=>$request->get('term'),
         'companyCode'=>$auUser->companyCode,
         'note' => $request->get('note'),
         'status'=>self::OT_CR_STATUS,
         'datenum'=>$days,
         'oriNum'=>$days,
        
                ]);
        $overtime->save();
          return back();
    }
      public function createDayOff(Request $request)
    { 
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
      $auUser=Tck_user::findOrFail($user->id);
      $from =  Carbon::parse($request->get('dateDOFrom'));
     // get dayofflist and only get working day
      $dayOffLSTs=CommonTimeFunction::checkHoliday($request->get('dateDOFrom'),$request->get('dateDOTo'));
      $this->validateOT($request->get('dateDOFrom'),$request->get('dateDOTo'));
     // get compensation leave
      $dateOTlist=tck_overtime::where('usrID','=',$auUser->code)
      ->whereRaw('month(date)='.$from->month.' and statusCP=402 and datenum >0 ')
      ->orderBy('date','ASC')
      ->get();
       $doType=self::OT_CR_ANNUAL; if (!is_null($dateOTlist)) { 
       $doType=self::OT_CP_STATUS;
       } 
      $i=0;

      $doID = DB::table('tck_dayoff')->insertGetId(
        [ 'fromDate'  =>$request->get('dateDOFrom'),
          'fromTerm'  =>$request->get('termFrom'),
          'toDate'=>$request->get('dateDOTo'),
          'toTerm'=>$request->get('termTo'),
          'note'=>$request->get('note'),
          'status'=>self:: OT_CR_STATUS,
          'usrCode'=>$auUser->code,
          'type' =>$doType,
            ]
          );  


       foreach ($dayOffLSTs as $dayOffLST => $value) {    
        $date=$value->date;
        $cpmain=0;
      if ($i==0 && $request->get('termFrom')=='502' && $value->typeName=='WO') {
           for ($f=0; $f <sizeof($dateOTlist) ; $f++) { 
              if ($dateOTlist[$f]->datenum >=0.5) {
                $this->insertTKdayoffB($doID,$date,0,$dateOTlist[$f]->id);
                $dateOTlist[$f]->datenum-=0.5;
                $f=sizeof($dateOTlist);
                $cpmain=1;
               
              }
           }

        }  elseif ($value->typeName=='WO') 
        { 
          for ($j=0; $j <sizeof($dateOTlist) ; $j++) { 
           
           if ($dateOTlist[$j]->datenum>=1) {
              $this->insertTKdayoffB($doID,$date,$dateOTlist[$j]->id,$dateOTlist[$j]->id);     
               $dateOTlist[$j]->datenum-=1;     
            $j=sizeof($dateOTlist);
            $cpmain=1;
           } elseif ($dateOTlist[$j]->datenum >=0.5) {
            for ($k=$j+1; $k <sizeof($dateOTlist) ; $k++) { 
              if ($dateOTlist[$k]->datenum>=0.5) {
          $this->insertTKdayoffB($doID,$date,$dateOTlist[$j]->id,$dateOTlist[$k]->id); 
            $dateOTlist[$j]->datenum-=0.5;  
             $dateOTlist[$k]->datenum-=0.5;         
            $j=sizeof($dateOTlist);
             $k=sizeof($dateOTlist);
             $cpmain=1;
               
              }
            }
           }

          }

        }  elseif  ($i==sizeof($dayOffLSTs)-1 && $request->get('termTo')=='501'  && $value->typeName=='WO') {
         
                       for ($e=0; $e <sizeof($dateOTlist) ; $e++) { 

              if ($dateOTlist[$e]->datenum >=0.5) {
                $this->insertTKdayoffB($doID,$date,$dateOTlist[$e]->id,0);
                $dateOTlist[$e]->datenum-=0.5;
                $e=sizeof($dateOTlist);
                $cpmain=1;
              }
           }
       
        } 
        if ($cpmain==0 ) {
          if ($i==0 && $request->get('termFrom')=='502' && $value->typeName=='WO'){
            $this->insertTKdayoffB($doID,$date,0,-1);
            $auUser->annualLeaveDate -=0.5;  
          } elseif ($i==sizeof($dayOffLSTs)-1 && $request->get('termTo')=='501'  && $value->typeName=='WO') {
           $this->insertTKdayoffB($doID,$date,-1,0);
            $auUser->annualLeaveDate -=0.5; 
          } elseif ($value->typeName=='WO') {
            $this->insertTKdayoffB($doID,$date,-1,-1);
            $auUser->annualLeaveDate -=1;  
            }
        }
        $i++;
        
      }
      $this->updateOvertime($dateOTlist,$auUser);
      $auUser->save();

      
        
      }
      /// Truong hop nhieu ngay, insert 1 ngay thanh 2 row: AM,PM

     
      public function dayoffSearch(Request $request)
    {

    }
    
      public function validateOT($from,$to)
       {
       
        if ($from>$to) {
                $this->validate($request,[
                'fromTime' => 'required|email',
        ],[
         ' Wrong time overtime input '
          
        ]);
        }
       
       }
          public function validateDuplicateOT($from,$to)
       {
       
        if ($from>$to) {
                $this->validate($request,[
                'fromTime' => 'required|email',
        ],[
         ' Wrong time overtime input '
          
        ]);
        }
       
       }
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
                          ]
                          );
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

       public function validateDayoff($request)
       {
           $user = Auth::user();
     $userH = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.id','=','users.id')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest, users.role as role')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();
         $from = $request->get('dateDOFrom');
         $to =  $request->input('dateDOTo');
          $fromTerm =  $request->get('termFrom');
         $toTerm = $request->input('termTo');
         $objID=$request->input('objID');
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
      ->where('tck_dayoff.id','<>',$objID)
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
 
       }
        public function validateEditOT($from,$to,$request,$userCode)
       {
       //  DB::connection()->enableQueryLog();
        $objID=$request->input('objID');
        if ($from>$to) {
                $this->validate($request,[
                'fromDate' => 'required|email',
        ],[
         ' Wrong time overtime input '
        ]);
        }
          $dateOT = Carbon::parse($request->input('dateOT'));
          $from="'".$from.":00'";
          $to="'".$to.":00'";
          $tblOT=DB::table('tck_overtime') ->where('date','=',$dateOT)
           ->where('usrID','=',$userCode) ->where('id','=',$objID)
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

         public function appAprove($id,$type,$idType,$idlist,$typedblist,$message)
    {

      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
                // create sesion
              if (Session::has('ssApprove')) {
                    Session::keep('ssApprove');
                    }
         // end session
      $master=Tck_master::all();
      $masterTerm=$master;
       $masterFrom=$master;
        $masterTo=$master;
        $dayoffDetail;
        $data;$daysTo;
        $dayss=0; $arrayId= $arrayTypedb= array();
        $arrayId= explode(';', $idlist);
         $arrayTypedb= explode(';', $typedblist);
         
        $nextType=$previousType=$next=$previous=$idNum=$lenghtArr=$previousTypeID= $nextTypeID=0;
          foreach ($arrayId as $key => $objId) {
            $lenghtArr++;
          }

        foreach ($arrayId as $key => $objId) {
         if ($objId==$idType) {
           // next
            if ($idNum==0) {
           $previous=0;
          } else {
            $previous= substr($arrayId[$idNum-1] ,2);
          $previousType=$arrayTypedb[$idNum-1];
          $previousTypeID=$arrayId[$idNum-1] ;
          //$nextTxt th
           }
          if ($idNum== $lenghtArr-2 && $idNum-1>=0) {
         
             $previous= substr($arrayId[$idNum-1] ,2);

           $next=0;
          } else {
            if(isset($arrayId[$idNum+1] ))
            {     $next= substr($arrayId[$idNum+1] ,2);;
                 $nextTypeID=$arrayId[$idNum + 1] ;
          $nextType=$arrayTypedb[$idNum+1];
        } else {$next=0;} 
          }
          
          
     
          
          
         }
         $idNum++;
        }
       
       

      $userH = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.code','=','users.code')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest,users.role')
                    ->where("tck_user.code","=",  $user->code)
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
      ->select(['ot.oriNum as oriNum','ot.datenum as datenum','ot.date as date','termtbl.name as term','typetbl.name as mtype','statustbl.name as mstatus','note',DB::raw('null as toDate'),DB::raw('null as mtermFrom '),DB::raw('null as mtermTo')]) ->orderBy('date','ASC') ->get();
        
      $deleteObjOVT;$deleteDayoff;$usnames;

       if ($type =="401" || $type=="402"|| $type=="405") {
       
          $deleteObjOVT = DB::table('tck_overtime')
          ->leftjoin('tck_master as termtbl', 'tck_overtime.term','=','termtbl.val')
          ->leftjoin('tck_master as statustbl', 'tck_overtime.status','=','statustbl.val')
          ->leftjoin('tck_master as typetbl', 'tck_overtime.statusCP','=','typetbl.val')
          ->join('tck_user as us','us.code','=','tck_overtime.usrID')
          ->join('tck_user as ma','ma.id','=','us.ma')
          ->join('tck_user as mb','mb.id','=','us.mb')
          ->where("tck_overtime.id","=", $id)->select(['tck_overtime.*','termtbl.name as termName'])
          ->whereRaw('( us.code ='.$userH->code.' )')
          ->where('us.companyCode','=',$userH->companyCode)
          ->select([ 'us.name as usnames', 'tck_overtime.*','ma.name as maName','mb.name as mbName','statustbl.name as mstatus','typetbl.name as type','termtbl.name as termName'])
          ->first();

  
         
           $days = DB::table('tck_dayyear as dy')
          ->leftjoin('tck_print as pr', 'pr.date', '=',
           DB::raw('dy.date and pr.code='.$deleteObjOVT->usrID))
          ->where("dy.date","=", $deleteObjOVT->date)
          ->selectRaw('dy.*,pr.attendance as attendance,pr.leaving as leaving')
         ->first();
      $rest='0';$usnames=$deleteObjOVT->usnames;
       // Tính ra thời gian nghỉ rest nếu attendance,leaving nằm giữa rest time thì set rest=1:00 
      if ($days->attendance< $userH->restE && $days->leaving< $userH->restE) {
         $rest=$userH->rest;
      }
      // Get dayoffDetail 
   

      // End Get dayoffDetail

       } else {
        $deleteDayoff= DB::table('tck_dayoff')
        ->join('tck_user as us','us.code','=','tck_dayoff.usrCode')
        ->leftjoin('tck_user as ma','ma.id','=','us.ma')
        ->leftjoin('tck_user as mb','mb.id','=','us.mb')
        ->leftjoin('tck_master as termtbl', 'tck_dayoff.fromTerm','=','termtbl.val')
        ->leftjoin('tck_master as typetbl', 'tck_dayoff.type','=','typetbl.val')
        ->leftjoin('tck_master as statustbl', 'tck_dayoff.status','=','statustbl.val')
        ->leftjoin('tck_master as termtotbl', 'tck_dayoff.toTerm','=','termtotbl.val')
        ->where('tck_dayoff.id','=',$id)
        ->whereRaw('( us.code ='.$userH->code.' )')
        ->where('us.companyCode','=',$userH->companyCode)
        ->select(['us.name as usnames','tck_dayoff.*','termtbl.name as fromName','termtotbl.name as toName','ma.name as maName','mb.name as mbName','statustbl.name as statusName','typetbl.name as typeName'])
        ->first();
        $usnames=$deleteDayoff->usnames;
          $data=DB::table('tck_dayoffb as b')
          ->leftjoin('tck_dayoff as do','do.id','=','b.doID') 
          ->leftjoin('tck_overtime as amDB','b.amID','=','amDB.id') 
          ->leftjoin('tck_overtime as pmDB','b.pmID','=','pmDB.id')  
          ->leftjoin('tck_master as statustbl','do.status','=','statustbl.val')  
             ->leftjoin('tck_dayyear as y', 'b.date','=','y.date')
          ->where('do.id','=',$id)
          ->select('b.date','statustbl.name as status','amDB.date as amDB','pmDB.date as pmDB','b.amID','b.pmID')
          ->orderBy('date')
          ->get();
         $dayss= CommonTimeFunction::fcn_CountDate($deleteDayoff->fromDate,$deleteDayoff->fromTerm,$deleteDayoff->toDate,$deleteDayoff->toTerm);
             $days = DB::table('tck_dayyear as dy')
          ->leftjoin('tck_print as pr', 'pr.date', '=',
           DB::raw('dy.date and pr.code='.$deleteDayoff->usrCode))
          ->where("dy.date","=", $deleteDayoff->fromDate)
          ->selectRaw('dy.*,pr.attendance as attendance,pr.leaving as leaving,dy.nameX as nameX')
         ->first();
          $daysTo = DB::table('tck_dayyear as dy')
          ->leftjoin('tck_print as pr', 'pr.date', '=',
           DB::raw('dy.date and pr.code='.$deleteDayoff->usrCode))
          ->where("dy.date","=", $deleteDayoff->toDate)
          ->selectRaw('dy.*,pr.attendance as attendance,pr.leaving as leaving,dy.nameX as nameX')
         ->first();
         
       }
       
        if (Session::has('backUrl')) {

            Session::keep('backUrl');
            }
  
       return view('application.applicationDetail', compact('usnames','master','userH','days','rest','masterTerm','masterFrom','masterTo','dayAlls','deleteObjOVT','deleteDayoff','type','data','dayss'
        ,'idType','idlist','next','previous','nextType','previousType','previousTypeID','nextTypeID','typedblist','message','daysTo'));


    }
        public function fcn_ArrToString($array)
    {
      $result='';
      foreach($array as $obj) {  
            $result .= $obj->idType.";";  
        }
        return  $result;
    }
       public function fcn_ArrTypeToString($array)
    {
      $result='';
      foreach($array as $obj) {  
            $result .= $obj->typedb.";";  
        }
        return  $result;
    }

}