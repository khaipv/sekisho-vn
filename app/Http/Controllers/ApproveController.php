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
use App\Tck_overtime;
use DB;
use App\Library\CommonTimeFunction;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Session;
use Illuminate\Support\Facades\Redirect;
class ApproveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     const OT_CR_STATUS  =101; 
     const OT_EDIT_STATUS  =108; 
    const OT_CP_STATUS  =402; 
    const OT_CR_CODE  =401; 
    const OT_CR_ANNUAL=201;
    const OT_CR_CP=202;
    const OT_DELETE_CP=404;

    const MAN_DENY_1=103;
    const MAN_DENY_2=104;
    const MAN_APP_1=105;
    const MAN_APP_2=106;
    const MAN_ROLE=0;
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
                    ->leftjoin('users', 'tck_user.id','=','users.id')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest, users.role as role')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();
      $today = Carbon::now();
     $staffLst=DB::table('users')
               ->whereNotNull('sort')
                ->get();
  
      // CREATE man_list : nếu user đăng nhập là mannager 1 (ma=0,mb=2) thì search ra các 
      // status= created(101 ) Nếu user là manager 2 (ma=0,mb=0) thì search ra các status đã được /
    //approve bởi man1 (105)            
           $man_list='()';
           if ($userH->ma==0 && $userH->mb==0) {
                 $man_list=' (101,105) ';
                } elseif ($userH->ma==0 && $userH->mb <> 0) {
                 $man_list=' (101) ';
                }    
     // end man_list           
      // select union day
             
      $dayOTs = DB::table('tck_overtime as ot')
       ->join('tck_user as us','us.code','=','ot.usrID')
       ->leftjoin('tck_master as typetbl', 'ot.statusCP','=','typetbl.val')
       ->leftjoin('tck_master as statustbl', 'ot.status','=','statustbl.val')
       ->leftjoin('tck_master as termtbl', 'ot.term','=','termtbl.val')
       ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
             ->whereRaw(' ( ot.status  in '.$man_list.' )')
       ->where('us.companyCode','=',$userH->companyCode)
        ->whereRaw(' month( ot.date)>= '.Carbon::now()->month)
        
       ->select(["ot.statusCP as typedb",'ot.id as id','ot.date as date','typetbl.name as mtype','statustbl.name as mstatus','note',DB::raw('null as toDate'),DB::raw('termtbl.name as mtermFrom '),DB::raw('null as mtermTo '),'ot.status as statusCode','us.code as usCode','us.name as usName','ot.created_at as created','ot.mannote1','ot.mannote2',DB::raw(" concat('ot',ot.id) as idType ")]);
       $dayOffs = DB::table('tck_dayoff as off')
       ->join('tck_user as us','us.code','=','off.usrCode')
        ->leftjoin('tck_master as statustbl', 'off.status','=','statustbl.val') 
        ->leftjoin('tck_master as typetbl', 'off.type','=','typetbl.val')   
        ->leftjoin('tck_master as termFromtbl', 'off.fromterm','=','termFromtbl.val')
        ->leftjoin('tck_master as termTotbl', 'off.toterm','=','termTotbl.val')
        ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
       
         ->whereRaw(' ( off.status  in '.$man_list.' )')
          

        ->whereRaw(' MONTH(off.fromDate) >= '.Carbon::now()->month)
        ->where('us.companyCode','=',$userH->companyCode)
        ->select(["off.type as typedb",'off.id as id','off.fromDate as date', DB::raw('typetbl.name as mtype'),'statustbl.name as mstatus','note','off.todate as toDate','termFromtbl.name as mtermFrom','termTotbl.name as mtermTo ','off.status as statusCode','us.code as usCode','us.name as usName','off.created_at as created','off.mannote1','off.mannote2' ,DB::raw(" concat('of',off.id)  as idType ")]);
        $dayAlls=$dayOTs ->union($dayOffs)->orderBy('usCode','date')->get();  
         $idlist=$this->fcn_ArrToString($dayAlls);
          $typedblist=$this->fcn_ArrTypeToString($dayAlls);
              $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssApprove', $links);
         return view('approve.approve', compact('masters','master','userH','dayAlls','mastersType','today','firstDay','lastDay',
          'staffLst','idlist','typedblist'));
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
                    ->leftjoin('users', 'tck_user.code','=','users.code')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest,users.role')
                    ->where("users.id","=",  $user->id)
                    // ->where("tck_user.year","=",  $user->id)
                    ->first();
      $t = Carbon::now();
      $staffLst=DB::table('users')
               ->whereNotNull('sort')
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
           
         return view('approve.approve', compact('masters','master','userH','dayAlls'
          ,'mastersType','staffLst','idlist','typedblist'));
      
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

            $firstDay ;
      $lastDay ;
      $masters=Tck_master::all();
      $mastersType=Tck_master::where('type','=','app') ->orWhere('type','=','dayoff')->get();
      $userH = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.id','=','users.id')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest, users.role as role')
                    ->where("tck_user.id","=",  $user->id)
                    ->first();
      $today = Carbon::now();
       $staffLst=DB::table('users')
               ->whereNotNull('sort')
                ->get();
  
        $auUser=Tck_user::findOrFail($user->id);
          $acceptArr=$idArr=$noteArr=$denyArr=array();
        $acceptArr=$request->accept;
        $denyArr=$request->deny;
        $noteArr=$request->note;
         $typedbArr=$request->typedb;
         $i=0;
        $idArr=$request->timeSID;
        $idOTSearch=$idOFSearch="(0";
        foreach ($idArr as $key => $idList) {
           $id= substr($idList, 2,strlen($idList)); 
         if (substr($idList, 0,2) =='ot' ) {
           $idOTSearch.=",".$id;
         } elseif (substr($idList, 0,2) =='of') {
            $idOFSearch.=",".$id;
         }
          if (!is_null($acceptArr)&& in_array($idList,$acceptArr)) {
          $this->fcn_CKappAproveMan($id,$typedbArr[$i],$noteArr[$i],$auUser);
          }
           if (!is_null($denyArr)&& in_array($idList,$denyArr)) {
            $this->fcn_CKappDenyMan($id,$typedbArr[$i],$noteArr[$i],$auUser);

          }
          $i++;

        }
        $dayAlls =$this->fcn_getAlllist($idOTSearch,$idOFSearch);
              $idlist=$this->fcn_ArrToString($dayAlls);
          $typedblist=$this->fcn_ArrTypeToString($dayAlls);
              // create sesion
              if (Session::has('ssApprove')) {
                    Session::keep('ssApprove');
                    }
         // end session
         return view('approve.approve', compact('masters','master','userH','dayAlls','mastersType','today','firstDay','lastDay',
          'staffLst','idlist','typedblist'));
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
      public function appBack()
    {
               if (Session::has('ssApprove')) {
                    Session::keep('ssApprove');
                              return ($url = Session::get('ssApprove')) 
   ? Redirect::to($url[0]) // Will redirect 2 links back 
   : 
             $this->index();
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
        $idType2=$idType;
        // vì có yêu cầu của Araki, nên sau khi remove items được Excute thì remove type của 
        // items đó trong $typedblist do vậy xác định lại $type :

        $type=substr($idType, 0,2);
        $dayss=0; $arrayId= $arrayTypedb= array();
        $arrayId= explode(';', $idlist);
         $arrayTypedb= explode(';', $typedblist);
         
        $nextType=$previousType=$next=$previous=$idNum=$lenghtArr=$previousTypeID= $nextTypeID=0;
           $lenghtArr= sizeof($arrayId);
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
                    ->leftjoin('users', 'tck_user.id','=','users.id')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest,users.role ,users.id as id')
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
      ->select(['ot.oriNum as oriNum','ot.datenum as datenum','ot.date as date','termtbl.name as term','typetbl.name as mtype','statustbl.name as mstatus','note',DB::raw('null as toDate'),DB::raw('null as mtermFrom '),DB::raw('null as mtermTo')]) ->orderBy('date','ASC') ->get();
        
      $deleteObjOVT;$deleteDayoff;$usnames;

       if ($type =="ot") {
          $deleteObjOVT = DB::table('tck_overtime')
          ->leftjoin('tck_master as termtbl', 'tck_overtime.term','=','termtbl.val')
          ->leftjoin('tck_master as statustbl', 'tck_overtime.status','=','statustbl.val')
          ->leftjoin('tck_master as typetbl', 'tck_overtime.statusCP','=','typetbl.val')
          ->join('tck_user as us','us.code','=','tck_overtime.usrID')
          ->join('tck_user as ma','ma.id','=','us.ma')
          ->join('tck_user as mb','mb.id','=','us.mb')
          ->where("tck_overtime.id","=", $id)->select(['tck_overtime.*','termtbl.name as termName'])
          ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
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
      // trả type về đúng giá trị của nó : 
      $type=$deleteObjOVT->statusCP;

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
        ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
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
          $type=$deleteDayoff->type;
         
       }
       
        if (Session::has('backUrl')) {

            Session::keep('backUrl');
            }
   
       return view('approve.approveDetail', compact('usnames','master','userH','days','rest','masterTerm','masterFrom','masterTo','dayAlls','deleteObjOVT','deleteDayoff','type','data','dayss' ,'idType2'
        ,'idType','idlist','next','previous','nextType','previousType','previousTypeID','nextTypeID','typedblist','message','daysTo'));


    }

/// theo yeu cau cua Araki san , sau khi manager approve, loai bo data da duoc xu ly trong list
 public function appAproveExecute($id,$type,$idType,$idlist,$typedblist,$message)
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
        $idType2=$idType;
        // vì có yêu cầu của Araki, nên sau khi remove items được Excute thì remove type của 
        // items đó trong $typedblist do vậy xác định lại $type :

        $type=substr($idType, 0,2);


        $dayss=0; $arrayId= $arrayTypedb= array();
        $arrayId= explode(';', $idlist);
         $arrayTypedb= explode(';', $typedblist);
         
        $nextType=$previousType=$next=$previous=$idNum=$lenghtArr=$previousTypeID= $nextTypeID=0;
           $lenghtArr= sizeof($arrayId);
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
                    ->leftjoin('users', 'tck_user.id','=','users.id')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest,users.role ,users.id as id')
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
      ->select(['ot.oriNum as oriNum','ot.datenum as datenum','ot.date as date','termtbl.name as term','typetbl.name as mtype','statustbl.name as mstatus','note',DB::raw('null as toDate'),DB::raw('null as mtermFrom '),DB::raw('null as mtermTo')]) ->orderBy('date','ASC') ->get();
        
      $deleteObjOVT;$deleteDayoff;$usnames;

       if ($type =="ot") {
          $deleteObjOVT = DB::table('tck_overtime')
          ->leftjoin('tck_master as termtbl', 'tck_overtime.term','=','termtbl.val')
          ->leftjoin('tck_master as statustbl', 'tck_overtime.status','=','statustbl.val')
          ->leftjoin('tck_master as typetbl', 'tck_overtime.statusCP','=','typetbl.val')
          ->join('tck_user as us','us.code','=','tck_overtime.usrID')
          ->join('tck_user as ma','ma.id','=','us.ma')
          ->join('tck_user as mb','mb.id','=','us.mb')
          ->where("tck_overtime.id","=", $id)->select(['tck_overtime.*','termtbl.name as termName'])
          ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
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
      // trả type về đúng giá trị của nó : 
      $type=$deleteObjOVT->statusCP;

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
        ->whereRaw('( us.ma ='.$userH->id.' or us.mb ='.$userH->id.' )')
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
          $type=$deleteDayoff->type;
         
       }
       
        if (Session::has('backUrl')) {

            Session::keep('backUrl');
            }
   $idListResult=str_replace( $idType,"",$idlist);
      $idlist=str_replace(";;",";",$idListResult);
      // sau khi execute, loai bo idtype hien tai ra khoi list. sau do khi click next, previous se 
      ///    ko goi lai cai cu nua
       return view('approve.approveDetail', compact('usnames','master','userH','days','rest','masterTerm','masterFrom','masterTo','dayAlls','deleteObjOVT','deleteDayoff','type','data','dayss' ,'idType2'
        ,'idType','idlist','next','previous','nextType','previousType','previousTypeID','nextTypeID','typedblist','message','daysTo'));


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
   
    private  function checkHoliday ($from,$to)
    {
    $tck_dayyear=DB::table('Tck_dayyear')
    ->where('date','>',$from)
     ->where('date','<',$to)
    
    ->get();
    $result=$tck_dayyear->keyBy('date');
    
    return $result;
    }
    
       public function appAproveMan(request $request)
      {
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         // self::validateOT($request);
          $auUser=Tck_user::findOrFail($user->id);


        $id=$request->input('requestID');
        $type=$request->input('requestType');

         if(Input::get('approve')){
          // approve OT
          if ($type=='401'||$type=='402'||$type=='405') {
           // check authentication
      $checkOTs = DB::table('tck_overtime as ot')
       ->join('tck_user as us','us.code','=','ot.usrID')
       ->where('ot.id','=',$id)
       ->select('us.ma as maid','us.mb as mbid')
       ->first();
       $approveOBJ=tck_overtime::findOrFail($id);

       // if man1 approve update
       if ($checkOTs->maid==$auUser->id) {
        
         $approveOBJ->mannote1=$request->input('note');
         $approveOBJ->status=self::MAN_APP_1;
         $approveOBJ->save();
       }
         if ($checkOTs->mbid==$auUser->id) {
         
         $approveOBJ->mannote2=$request->input('note');
         $approveOBJ->status=self::MAN_APP_2;
         $approveOBJ->save();
       }
            // end overtime
          } else { // approve dayoff 

             $checkOTs = DB::table('tck_dayoff as off')
       ->join('tck_user as us','us.code','=','off.usrCode')
       ->where('off.id','=',$id)
       ->select('us.ma as maid','us.mb as mbid')
       ->first();
       $approveOBJ=tck_dayoff::findOrFail($id);
        $dayoffB=DB::table('tck_dayoffb as off')
                  ->where('off.doid','=',$approveOBJ->id)
                  ->get();
              if ($checkOTs->maid==$auUser->id) {
         $approveOBJ->mannote1=$request->input('note');
         $approveOBJ->status=self::MAN_APP_1;
         $approveOBJ->save();
             foreach ($dayoffB as $key => $value) {
               $approveOBJB=tck_dayoffb::findOrFail($value->id);
                    $approveOBJB->status=self::MAN_APP_1;
                    $approveOBJB->save();
                  }  
       }
         if ($checkOTs->mbid==$auUser->id) {
         $approveOBJ->mannote2=$request->input('note');
         $approveOBJ->status=self::MAN_APP_2;
         $approveOBJ->save();
          foreach ($dayoffB as $key => $value) {
                     $approveOBJB=tck_dayoffb::findOrFail($value->id);
                    $approveOBJB->status=self::MAN_APP_2;
                    $approveOBJB->save();
                  }  
       }
          }

//end approve function
      }
      $message='Succes! application was approve';
       if(Input::get('deny')){
        // deny OverTime
        if ($type=='401'||$type=='402'||$type=='405'){
          // Deny Overtime
            $checkOTs = DB::table('tck_overtime as ot')
       ->join('tck_user as us','us.code','=','ot.usrID')
       ->where('ot.id','=',$id)
       ->select('us.ma as maid','us.mb as mbid')
       ->first();
       $approveOBJ=tck_overtime::findOrFail($id);
       // if man1 approve update
       if ($checkOTs->maid==$auUser->id) {
         $approveOBJ->mannote1=$request->input('note');
         $approveOBJ->status=self::MAN_DENY_1;
         $approveOBJ->save();
       }
        if ($checkOTs->mbid==$auUser->id) {
         $approveOBJ->mannote2=$request->input('note');
         $approveOBJ->status=self::MAN_DENY_2;
         $approveOBJ->save();
       }
       // End Deny Over time
        }
        // deny Dayoff
        if ($type=='201'||$type=='202'||$type=='204') {
        CommonTimeFunction:: denyDayoff($id,$request->input('note'));
        }
        // Deny Work on Holiday should deny all dayoff used
        if ($type=='402'){
           $checkOTs = DB::table('tck_overtime as ot')
             ->join('tck_user as us','us.code','=','ot.usrID')
             ->where('ot.id','=',$id)
             ->select('us.ma as maid','us.mb as mbid')
             ->first();
            $dayoffbs=tck_dayoffb::where('amID','=',$id)->orWhere('pmID','=',$id)
             ->select('doID')->groupBy('doID')->get();
             foreach ($dayoffbs as $key => $value) {
              CommonTimeFunction:: denyDayoff($value->doID,$request->input('note'));
             }
        }
      // end function deny
             // return Redirect::back()->with('message', 'Succes! application was deny');
            //  return redirect()->back()->with('message', 'IT WORKS!');
          //  dd("a");
       $message='Succes! application was deny';
      }
/*          dd("b");
      return redirect()->back()->with('message', 'Succes! application was approved!');
            //  return Redirect::back()->with('message', 'Succes! application was approve');*/
      // Truong hop row cuoi cung thi next se khong co nen trỏ next ve chính nó
     // dd( $request->input('next'));  $request->input('requestID');
     // dd( $request->input('requestID'));
      // dd($request->input('requestID').'/'.$request->input('requestType').$request->input('idType').
      //   '/' );
      $exidType='';
      $exidType=$request->input('idType');

   
     return  $this->   appAproveExecute(
         $request->input('requestID'),
         $request->input('requestType'),
          $exidType,
         $request->input('idlist'),
         $request->input('typedblist'),
         $message
         );
 
      }
       public function fcn_CKappAproveMan($id,$type,$note,$auUser)
{
          // approve OT
          if ($type=='401'||$type=='402'||$type=='405') {
           // check authentication
      $checkOTs = DB::table('tck_overtime as ot')
       ->join('tck_user as us','us.code','=','ot.usrID')
       ->where('ot.id','=',$id)
       ->select('us.ma as maid','us.mb as mbid')
       ->first();
       $approveOBJ=tck_overtime::findOrFail($id);

       // if man1 approve update
       if ($checkOTs->maid==$auUser->id) {
        
         $approveOBJ->mannote1=$note;
         $approveOBJ->status=self::MAN_APP_1;
         $approveOBJ->save();
       }
         if ($checkOTs->mbid==$auUser->id) {
         
         $approveOBJ->mannote2=$note;
         $approveOBJ->status=self::MAN_APP_2;
         $approveOBJ->save();
       }
            // end overtime
          } else { // approve dayoff 

             $checkOTs = DB::table('tck_dayoff as off')
       ->join('tck_user as us','us.code','=','off.usrCode')
       ->where('off.id','=',$id)
       ->select('us.ma as maid','us.mb as mbid')
       ->first();
       $approveOBJ=tck_dayoff::findOrFail($id);
        $dayoffB=DB::table('tck_dayoffb as off')
                  ->where('off.doid','=',$approveOBJ->id)
                  ->get();
              if ($checkOTs->maid==$auUser->id) {
         $approveOBJ->mannote1=$note;
         $approveOBJ->status=self::MAN_APP_1;
         $approveOBJ->save();

             foreach ($dayoffB as $key => $value) {
               $approveOBJB=tck_dayoffb::findOrFail($value->id);
                    $approveOBJB->status=self::MAN_APP_1;
                    $approveOBJB->save();
                  }  
       }
         if ($checkOTs->mbid==$auUser->id) {
         $approveOBJ->mannote2=$note;
         $approveOBJ->status=self::MAN_APP_2;
         $approveOBJ->save();
          foreach ($dayoffB as $key => $value) {
                     $approveOBJB=tck_dayoffb::findOrFail($value->id);
                    $approveOBJB->status=self::MAN_APP_2;
                    $approveOBJB->save();
                  }  
       }
          }
//end approve function
      }
  public function fcn_CKappDenyMan($id,$type,$note,$auUser)
    {
        // deny OverTime
        if ($type=='401'||$type=='402'||$type=='405'){
          // Deny Overtime
            $checkOTs = DB::table('tck_overtime as ot')
       ->join('tck_user as us','us.code','=','ot.usrID')
       ->where('ot.id','=',$id)
       ->select('us.ma as maid','us.mb as mbid')
       ->first();
       $approveOBJ=tck_overtime::findOrFail($id);
       // if man1 approve update
       if ($checkOTs->maid==$auUser->id) {
         $approveOBJ->mannote1=$note;
         $approveOBJ->status=self::MAN_DENY_1;
         $approveOBJ->save();
       }
        if ($checkOTs->mbid==$auUser->id) {
         $approveOBJ->mannote2=$note;
         $approveOBJ->status=self::MAN_DENY_2;
         $approveOBJ->save();
       }
       // End Deny Over time
        }
        // deny Dayoff
        if ($type=='201'||$type=='202'||$type=='204') {

        CommonTimeFunction:: denyDayoff($id,$note);
        }
        // Deny Work on Holiday should deny all dayoff used
        if ($type=='402'){
           $checkOTs = DB::table('tck_overtime as ot')
             ->join('tck_user as us','us.code','=','ot.usrID')
             ->where('ot.id','=',$id)
             ->select('us.ma as maid','us.mb as mbid')
             ->first();
            $dayoffbs=tck_dayoffb::where('amID','=',$id)->orWhere('pmID','=',$id)
             ->select('doID')->groupBy('doID')->get();
             foreach ($dayoffbs as $key => $value) {
              CommonTimeFunction:: denyDayoff($value->doID,$note);
             }
        }
    }
    public function fcn_getAlllist($idOTSearch,$idOFSearch)
    {
       $dayOTs = DB::table('tck_overtime as ot')
       ->join('tck_user as us','us.code','=','ot.usrID')
       ->leftjoin('tck_master as typetbl', 'ot.statusCP','=','typetbl.val')
       ->leftjoin('tck_master as statustbl', 'ot.status','=','statustbl.val')
       ->leftjoin('tck_master as termtbl', 'ot.term','=','termtbl.val')
      
      
        ->whereRaw(' ot.id in '.$idOTSearch .") ")
       ->select(["ot.statusCP as typedb",'ot.id as id','ot.date as date','typetbl.name as mtype','statustbl.name as mstatus','note',DB::raw('null as toDate'),DB::raw('termtbl.name as mtermFrom '),DB::raw('null as mtermTo '),'ot.status as statusCode','us.code as usCode','us.name as usName','ot.created_at as created','ot.mannote1','ot.mannote2',DB::raw(" concat('ot',ot.id) as idType ")]);
       $dayOffs = DB::table('tck_dayoff as off')
       ->join('tck_user as us','us.code','=','off.usrCode')
        ->leftjoin('tck_master as statustbl', 'off.status','=','statustbl.val') 
        ->leftjoin('tck_master as typetbl', 'off.type','=','typetbl.val')   
        ->leftjoin('tck_master as termFromtbl', 'off.fromterm','=','termFromtbl.val')
        ->leftjoin('tck_master as termTotbl', 'off.toterm','=','termTotbl.val')
         ->whereRaw(' off.id in '.$idOFSearch.") ")
        ->select(["off.type as typedb",'off.id as id','off.fromDate as date', DB::raw('typetbl.name as mtype'),'statustbl.name as mstatus','note','off.todate as toDate','termFromtbl.name as mtermFrom','termTotbl.name as mtermTo ','off.status as statusCode','us.code as usCode','us.name as usName','off.created_at as created','off.mannote1','off.mannote2' ,DB::raw(" concat('of',off.id)  as idType ")]);
        $dayAlls=$dayOTs ->union($dayOffs)->orderBy('usCode','date')->get();  
        return  $dayAlls;
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