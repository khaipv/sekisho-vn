<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Candidates;
use App\Division;
use App\Master;
use Carbon\Carbon; 
use App\Province;
use App\Major;
use App\Universities;
use App\Province2;
use App\Occupation;
use App\Items;
use App\Follow_candidate;
use App\Can_edu;
use App\Mastercandidate;
use DB;
use App\User;
use Session;
use Redirect;
use App\Library\CommonFunction;
use App\Library\CommonValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use App\Exports\DataExport;
use Excel;
class CandidateController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
     $items=Items::   orderBy('name', 'ASC')->get();
     $subitemsIT =    $items;
     $subitemsTech =  $items;
     $subitemsLab =   $items;
     $subitemsFin =   $items;
     $subitemsOther = $items;
     $master=DB::table('master') ->orderBy('sort', 'DESC')->get();
     $kinds=DB::table('can_kind') ->orderBy('sort', 'ASC')->get();
     $gkinds=DB::table('can_gkind') ->orderBy('sort', 'DESC')->get();

     $source=$master;
     $majors=Major::orderBy('sort', 'ASC')->get();
     $province = Province2::orderBy('SortOrder', 'ASC') ->get();

     $birthPlace=$province;
     $english_levels=$master;
     $korean=$chinese=$engeval=$jpeval=$master;
     $japanese_levels=$master;
     $workplace=$master;
     $emailStatus=$mobileStatus=$master;
     $workplaceLst= $workplaceLstT= $workplaceLstN=$province;
     $majors=Major::orderBy('sort', 'ASC')->get();
     $university=Universities::where('sort','<',1000)->orWhere('sort','=',2000)
     ->orderBy('sort', 'ASC')->get();
     $users = DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();
     $arrayKindName = $this->kindArray();
     $arrayGKindName= $this->kindGArray();

     return view('candidate.createcandidate',compact('master','province','birthPlace'
      ,'workplaceLstT','workplaceLstN','emailStatus','mobileStatus','arrayKindName'

      ,'gkinds'   

      , 'arrayGKindName','korean','chinese','engeval','jpeval',
      'english_levels','japanese_levels','workplace','subitemsIT','subitemsTech','subitemsLab','users','workplaceLst' ,'subitemsFin','subitemsOther','majors','university','source'));  
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
          //Validation


     $this->validateCandi($request);
     $this->validateInputMail($request);
     $workplaceTxt='';
     if(is_null( $request->get('workplaceTxt'))) 
      { $workplaceTxt= $request->get('workplaceTxt');} 
    $maxnum = CommonFunction::getAutoInscrement("candidates");
    $maxcode=$maxnum+10000000;
    $cannum=substr($maxcode, -7);
    $txt_other='';
    $other_admin='';
    $other_man='';
    $txt_otherIT='';
    $txt_otherTech='';
    $txt_otherWorkplace='';
    $workplace_select='';
    $major_select='';
    $foreigner=0;

    if(!empty($request->input('other_admin'))) 
      { $other_admin.=$request->input('other_admin').";" ;} 
    if(!empty($request->input('other_man'))) 
      { $other_man.=$request->input('other_man').";" ;} 
    if(!empty($request->input('other_subLab'))) 
      { $txt_other.=$request->input('other_subLab').";" ;} 
    if(!empty($request->input('other_subTech'))) 
      { $txt_otherTech.=$request->input('other_subTech').";" ;}
    if(!empty($request->input('other_subIT'))) 
      { $txt_otherIT.=$request->input('other_subIT').";" ;}
    if(!empty($request->input('otherWorkplace'))) 
      { $txt_otherWorkplace.=$request->input('otherWorkplace').";" ;}
    if(!empty($request->input('foreigner'))) 
      { $foreigner=$request->input('foreigner') ;} 

    $select_array=$this->requestArray($request);
    if ($request->get('workplace')==62||$request->get('workplace')==65) {
      $workplace_select =$this->spilitArray($request->input('workplace_select'));
    }


    $other_admin=rtrim($other_admin, "; ");
    $other_man=rtrim($other_man, "; ");
    $txt_other=rtrim($txt_other, "; ");
    $txt_otherIT=rtrim($txt_otherIT, "; ");
    $txt_otherTech=rtrim($txt_otherTech, "; ");
    $occ=  $this->occupationArray($select_array);
    $skill=$this->requestKindArray($request);
    $birth=$request->get('birthday');
    if (is_null($birth)) {
      $birth='1400-01-01';
            # code...
    }

    $canidates = new Candidates([
     'code' => $cannum,
     'firstName'=> $request->get('firstName'),
     'midleName'=> '' . $request->get('midleName'),
     'lastName'=> $request->get('lastName'),
     'firstNameJ'=> $request->get('firstNameJ'),
     'midleNameJ'=> $request->get('midleNameJ'),
     'lastNameJ'=> $request->get('lastNameJ'),
     'birth' => $birth,
     'email' => $request->get('email'),
     'sex'  => $request->get('sex'),
     'married'  => $request->get('married'),
     'mobile' => $request->get('mobile'),
     'university' => $request->get('university'),
     'birthPlace' => $request->get('province'),
     'currentAdd' => $request->get('address'),
     'eLevel' => $request->get('english'),
     'jLevel' => $request->get('japanese'),
     'toeic' => $request->get('toeic'),
     'workPlace' => $request->get('workplace'),
     'workPlaceTxt' =>$workplaceTxt,
     'graduates' => $request->get('graduate'),
     'majorsTxt'=> $request->get('majorsTxt'),
     'source' => $request->get('source'),
     'sourceTxt' => $request->get('sourceTxt'),
     'situation' => $request->get('situation'),
     'plan' => $request->get('plan'),
     'interview' => $request->get('interview'),
     'mandan' =>(string) $request->get('mandan'),
     'mandanDate' => $request->get('mandanDate'),
     'workcheck' =>(string) $request->get('workcheck'),
     'workDate' => $request->get('workDate'),
     'otherIT' => $txt_otherIT,
     'otherTech' => $txt_otherTech,
     'other' => $txt_other,
     'otherManu'=>$other_man,
     'otherAdmin'=>$other_admin,
     'contact' => $request->get('contact'),
     'mobilestatus' => $request->get('mobileStatus'),
     'staff' => $request->get('pic_s'),
     'korean' => $request->get('korean'),
     'chinese' => $request->get('chinese'),
     'engeval' => $request->get('engeval'),
     'jpeval' => $request->get('jpeval'),
     'evaluation' => $request->get('evaluation'),
     'evalDate' => $request->get(' evalDate'),
     'foreigner' =>$foreigner,
     'col100' => $occ[0],
     'exp100' => $occ[1],
     'col39' => $occ[2],
     'exp39' => $occ[3],
     'skill'=> $skill,
     'wpselect' =>$workplace_select,
     'majors' => $request->get('majors'),
   ]);
    $canidates->save();
   // return redirect('https://srv1.sekisho-vn.com/test/candidate/'.$canidates->id);
    return $this->show($canidates->id)->with('message', 'Succes! Candidate was created');
  }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
     $user = Auth::user();
     if (is_null($user) ) {
       return  redirect('/');
     }
     $idjob=0;
     $items=Items::   orderBy('name', 'ASC')->get();
     $subitemsIT =    $items;
     $subitemsTech =  $items;
     $subitemsLab =   $items;
     $subitemsFin =   $items;
     $subitemsOther = $items;
     $subManufacturing=$items;
     $subTechnology=$items;
     $subAdmin=$items;
     $subOther=$items;
     $type=$message='';
     $province = Province2::orderBy('SortOrder', 'ASC') ->get();
     $workplaceLst=$birthPlace=$province;
     $candi=$this -> findCandiByID($id);
     $occupation=$this ->findOCbycode($candi);
     $experience=$this ->findEXPbycode($candi);
     $educations=$this->findEduByCanID($id);
     $hisjoblst=$this->findHisJobByCanID($id);
     $actionlist=$this->findactionlist($id);
     $actionlast=$this->findactionlast($id);
     if(!is_null($actionlast))
     {
      $cleantime=substr($actionlast->time_start,0,5);
      $actionlast->time_start=$cleantime;
     }
    //  $cleantime=substr($actionlast->time_start,0,5);
    //  $actionlast->time_start=$cleantime;
     $queryI = Candidates::query()
     ->select('candidates.id',
      DB::raw("(CASE WHEN birth <'1500-01-01' THEN col1 ELSE birth END) AS birth2")

    )
     ->where("candidates.id","=", $id)
     ->first();

     $birth2=$queryI;
                // create session

     if (Session::has('ssCandidate')) {
       $url = Session::get('ssCandidate');
       $currentLink = URL::full(); 
       array_push($url, $currentLink); 
       Session::flash('ssCandidate', $url);

     }
     $masterOcc=Master::orderBy('sort', 'ASC')->get();    
     $majors=Major::orderBy('sort', 'ASC')->get();
     $major_select=explode(';',$candi->majors);   
     $workplace_selected=explode(';',$candi->wpselect);       
           // end create session
     $wpflage=0;
     foreach($workplaceLst as $copor){
      if (in_array($copor->Id,$workplace_selected ) && !is_null($copor->Id)) {
        $wpflage=1;
      }}

      $skillList=$this->stringSkill($candi->skill);
                  // start order-candi list
      $orderlist =DB::table('ca_OrderCandidates as oc')
      ->join('order','order.id','=','oc.idOrder')  
      ->leftjoin('division','order.divisionId','=','division.id') 
      ->leftjoin('client','division.companyid','=','client.id') 
      ->select( 'oc.id as ocID','oc.*','oc.idOrder as ocOrderID'
        ,'client.companyname as clientName','division.divisionname as divname','order.title','order.code as ordCode')
      ->where('idCandidates','=',$id)
      ->orderBy('oc.id', 'DESC')->get();  
       // dd($orderlist);
                  // end order candi list
      return view('candidate.detailcandidate', compact('candi','occupation','birth2' ,'majors'
        ,'major_select','workplaceLst','workplace_selected','masterOcc','wpflage'
        ,'skillList','orderlist','educations', 'type','message','hisjoblst','idjob'
        ,'subitemsIT','subitemsTech','subitemsLab','subitemsFin','subitemsOther','experience','actionlist','id','actionlast'));

    }
 public static function showCandiByIdType($id,$type,$message,$fromcr,$tocr,$idjob)
    {
     $user = Auth::user();
     if (is_null($user) ) {
       return  redirect('/');
     }
     $items=Items::   orderBy('name', 'ASC')->get();
     $subitemsIT =    $items;
     $subitemsTech =  $items;
     $subitemsLab =   $items;
     $subitemsFin =   $items;
     $subitemsOther = $items;
     $subManufacturing=$items;
     $subTechnology=$items;
     $subAdmin=$items;
     $subOther=$items;
    
     $province = Province2::orderBy('SortOrder', 'ASC') ->get();
     $workplaceLst=$birthPlace=$province;
     $candi= self:: findCandiByID($id);
     $occupation=self::findOCbycode($candi);
     $experience=self::findEXPbycode($candi);
     $educations=self::findEduByCanID($id);
     $hisjoblst=self::findHisJobByCanID($id);
     $actionlist=self::findactionlist($candi->id);
     $actionlast=self::findactionlast($candi->id);
    //  $actionlast->time_start=$actionlast->time_start->format('H:i'); 
     $queryI = Candidates::query()
     ->select('candidates.id',
      DB::raw("(CASE WHEN birth <'1500-01-01' THEN col1 ELSE birth END) AS birth2")

    )
     ->where("candidates.id","=", $id)
     ->first();

     $birth2=$queryI;
                // create session

     if (Session::has('ssCandidate')) {
       $url = Session::get('ssCandidate');
       $currentLink = URL::full(); 
       array_push($url, $currentLink); 
       Session::flash('ssCandidate', $url);

     }
     $masterOcc=Master::orderBy('sort', 'ASC')->get();    
     $majors=Major::orderBy('sort', 'ASC')->get();
     $major_select=explode(';',$candi->majors);   
     $workplace_selected=explode(';',$candi->wpselect);       
           // end create session
     $wpflage=0;
     foreach($workplaceLst as $copor){
      if (in_array($copor->Id,$workplace_selected ) && !is_null($copor->Id)) {
        $wpflage=1;
      }}

      $skillList=self::stringSkill($candi->skill);
                  // start order-candi list
      $orderlist =DB::table('ca_OrderCandidates as oc')
      ->join('order','order.id','=','oc.idOrder')  
      ->leftjoin('division','order.divisionId','=','division.id') 
      ->leftjoin('client','division.companyid','=','client.id') 
      ->select( 'oc.id as ocID','oc.*','oc.idOrder as ocOrderID'
        ,'client.companyname as clientName','division.divisionname as divname','order.title','order.code as ordCode')
      ->where('idCandidates','=',$id)
      ->orderBy('oc.id', 'DESC')->get();  
       // dd($orderlist);
                  // end order candi list
      return view('candidate.detailcandidate', compact('candi','occupation','birth2' ,'majors'
        ,'major_select','workplaceLst','workplace_selected','masterOcc','wpflage'
        ,'skillList','orderlist','educations', 'type','message','hisjoblst'
        ,'subitemsIT','subitemsTech','subitemsLab','subitemsFin','subitemsOther','experience'
        ,'fromcr','tocr','idjob','actionlist','id','actionlast'));

    }
    public static function showCandiByIdTypeThree($id,$type,$message,$fromcr,$tocr,$idjob,$occupationcr,$companycr,$positioncr, $detailcr)
    {
     $user = Auth::user();
     if (is_null($user) ) {
       return  redirect('/');
     }
     $items=Items::   orderBy('name', 'ASC')->get();
     $subitemsIT =    $items;
     $subitemsTech =  $items;
     $subitemsLab =   $items;
     $subitemsFin =   $items;
     $subitemsOther = $items;
     $subManufacturing=$items;
     $subTechnology=$items;
     $subAdmin=$items;
     $subOther=$items;
    
     $province = Province2::orderBy('SortOrder', 'ASC') ->get();
     $workplaceLst=$birthPlace=$province;
     $candi= self:: findCandiByID($id);
     $occupation=self::findOCbycode($candi);
     $experience=self::findEXPbycode($candi);
     $educations=self::findEduByCanID($candi->id);
     $hisjoblst=self::findHisJobByCanID($candi->id);
     $queryI = Candidates::query()
     ->select('candidates.id',
      DB::raw("(CASE WHEN birth <'1500-01-01' THEN col1 ELSE birth END) AS birth2")

    )
     ->where("candidates.id","=", $id)
     ->first();

     $birth2=$queryI;
                // create session

     if (Session::has('ssCandidate')) {
       $url = Session::get('ssCandidate');
       $currentLink = URL::full(); 
       array_push($url, $currentLink); 
       Session::flash('ssCandidate', $url);

     }
     $masterOcc=Master::orderBy('sort', 'ASC')->get();    
     $majors=Major::orderBy('sort', 'ASC')->get();
     $major_select=explode(';',$candi->majors);   
     $workplace_selected=explode(';',$candi->wpselect);       
           // end create session
     $wpflage=0;
     foreach($workplaceLst as $copor){
      if (in_array($copor->Id,$workplace_selected ) && !is_null($copor->Id)) {
        $wpflage=1;
      }}

      $skillList=self::stringSkill($candi->skill);
                  // start order-candi list
      $orderlist =DB::table('ca_OrderCandidates as oc')
      ->join('order','order.id','=','oc.idOrder')  
      ->leftjoin('division','order.divisionId','=','division.id') 
      ->leftjoin('client','division.companyid','=','client.id') 
      ->select( 'oc.id as ocID','oc.*','oc.idOrder as ocOrderID'
        ,'client.companyname as clientName','division.divisionname as divname','order.title','order.code as ordCode')
      ->where('idCandidates','=',$id)
      ->orderBy('oc.id', 'DESC')->get();  
       // dd($orderlist);
                  // end order candi list
      return view('candidate.detailcandidate', compact('candi','occupation','birth2' ,'majors'
        ,'major_select','workplaceLst','workplace_selected','masterOcc','wpflage'
        ,'skillList','orderlist','educations', 'type','message','hisjoblst'
        ,'subitemsIT','subitemsTech','subitemsLab','subitemsFin','subitemsOther','experience'
        ,'fromcr','tocr','idjob','occupationcr','companycr','positioncr','detailcr'));

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
     $master=DB::table('master') ->orderBy('sort', 'DESC')->get();
     $province = Province2::orderBy('SortOrder', 'ASC') ->get();
     $workplaceLstT=$workplaceLstN=$workplaceLst=$birthPlace=$province;
     $english_levels=$master;
     $japanese_levels=$master;
     $korean=$chinese=$engeval=$jpeval=$master;
     $emailStatus=$mobileStatus=$master;
     $workplace=$master;
     $items=Items::  orderBy('name', 'ASC')->get();
     $subitemsIT =    $items;
     $subitemsTech =  $items;
     $subitemsOther = $items;
     $subitemsAdmin =    $items;
     $subitemsManufacturing =    $items;
     $source=$master;
     $majors=Major::orderBy('sort', 'ASC')->get();
     $university=Universities::orderBy('sort', 'ASC')->get();
     $candi = DB::table('candidates')
     ->leftjoin('master as workp', 'candidates.workplace','=','workp.id')                
     ->leftjoin('master as eng', 'candidates.eLevel','=','eng.id')            
     ->leftjoin('master as ja', 'candidates.jLevel','=','ja.id')
     ->leftjoin('province2 as birtP', 'candidates.birthPlace', '=', 'birtP.Id')
     ->leftjoin('province2 as curAdd', 'candidates.currentAdd', '=', 'curAdd.Id')
     ->select('candidates.*', 'eng.name as eLevelName' , 'ja.name as jLevelName','birtP.Name as birth_Place','workp.Name as wpName','curAdd.name as currAdd',
      DB::raw("(CASE WHEN birth <'1500-01-01' THEN col1 ELSE birth END) AS birth2"))
     ->where("candidates.id","=", $id)
     ->first();
     $major_select=explode(';',$candi->majors);
     $workplace_selected=explode(';',$candi->wpselect);
         // dD($workplace_selected);
     $occupation=$copArr=explode(';',$candi->col100);
     $experience=$copArr=explode(';',$candi->exp100);
     $users = DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();
               // create session
     if (Session::has('ssCandidate')) {
       $url = Session::get('ssCandidate');
       $currentLink = URL::full(); 
       array_push($url, $currentLink); 
       Session::flash('ssCandidate', $url);
     }
           // end create session
            // skill 
     $arrayKindName = $this->kindArray();
     $arrayGKindName= $this->kindGArray();
     $gkinds=DB::table('can_gkind') ->orderBy('sort', 'DESC')->get();
     $arrKindEdit=explode(";", $candi->skill);
     $kinds=DB::table('can_kind') ->orderBy('sort', 'ASC')->get();
     return view('candidate.editcan', compact('candi','province','id','birthPlace','english_levels','japanese_levels','workplace','occupation','experience','subitemsIT','users',
      'subitemsTech','subitemsOther','majors','university','source','major_select','gkinds',  
      'arrKindEdit','kinds',
      'workplaceLstT','workplaceLstN','emailStatus','mobileStatus','arrayKindName','arrayGKindName','korean','chinese','engeval','jpeval'
      ,'workplace_selected','workplaceLst'));
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
     $this->validateCandi($request);
     $this->validateUDPMail($request, $id);
     $txt_other='';
     $txt_otherIT='';
     $other_admin='';
     $other_nanu='';
     $txt_otherTech='';
     $foreigner=0;
     $workplace_select ='';
     if(!empty($request->input('other_subIT'))) 
      { $txt_otherIT.=$request->input('other_subIT').";" ;} 
    if(!empty($request->input('other_subTech'))) 
      { $txt_otherTech.=$request->input('other_subTech').";" ;}
    if(!empty($request->input('other_subLab'))) 
      { $txt_other.=$request->input('other_subLab').";" ;}
    if(!empty($request->input('other_admin'))) 
      { $other_admin.=$request->input('other_admin').";" ;}
    if(!empty($request->input('other_nanu'))) 
      { $other_nanu.=$request->input('other_nanu').";" ;}
    $txt_other=rtrim($txt_other, "; ");
    $other_admin=rtrim($other_admin, "; ");
    $other_nanu=rtrim($other_nanu, "; ");
    $txt_otherIT=rtrim($txt_otherIT, "; ");
    $txt_otherTech=rtrim($txt_otherTech, "; ");
    $workplaceTxt='';
    $birth=$request->get('birthday');
    if (is_null($birth)) {
      $birth='1400-01-01';
            # code...
    }
    if(!empty($request->input('foreigner'))) 
      { $foreigner=$request->input('foreigner') ;} 
    if(!is_null( $request->get('workplaceTxt'))) 
      { $workplaceTxt= $request->get('workplaceTxt');} 
    $select_array=$this->requestArray($request);
    $occ=  $this->occupationArray($select_array);
    if ($request->get('workPlace')==62||$request->get('workPlace')==65) {
      $workplace_select =$this->spilitArray($request->input('workplace_select'));
    }
    $skill=$this->requestKindArray($request);
    $englishlevel= $request->get('english');
    if ($englishlevel==20) {
     $englishlevel=NULL;
   }

   $candi =  Candidates::find($id);
   $candi->firstName= $request->get('firstName');
   $candi->midleName= ''. $request->get('midleName');
   $candi->lastName= $request->get('lastName');
   $candi->firstNameJ= $request->get('firstNameJ');
   $candi->midleNameJ= $request->get('midleNameJ');
   $candi->lastNameJ= $request->get('lastNameJ');
   $candi->birth       =  $birth;
   $candi->email               =  $request->get('email');
   $candi->sex                 =  $request->get('sex');
   $candi->married             =  $request->get('married');
   $candi->mobile              =  $request->get('mobile');
   $candi->toeic      =  $request->get('toeic');
   $candi->birthPlace          =  $request->get('birthPlace');
   $candi->currentAdd          =  $request->get('currentAdd');
   $candi->eLevel              =  $englishlevel;
   $candi->toeic              =  $request->get('toeic');
   $candi->jLevel              =  $request->get('japanese');
   $candi->workPlace           =  $request->get('workPlace');
   $candi->workplaceTxt           = $workplaceTxt;
   $candi->graduates           =  $request->get('graduate');

   $candi->source              =  $request->get('source');
   $candi->sourceTxt              =  $request->get('sourceTxt');
   $candi->situation           =  $request->get('situation');
   $candi->plan              = LTRIM( $request->get('plan'));
   $candi->university     =  $request->get('university');
   $candi->mandan     =  $request->get('mandan');
   $candi->mandanDate     =  $request->get('mandanDate');
   $candi->workDate     =  $request->get('workDate');
   $candi->workcheck     =  $request->get('workcheck');
   $candi->otherIT     =  $txt_otherIT;
   $candi->otherTech     =  $txt_otherTech;
   $candi->other     =  $txt_other;
   $candi->otherManu     =  $other_nanu;
   $candi->otherAdmin   =  $other_admin;
   $candi->staff     =  $request->get('pic_s');
   $candi->changeJob=  $request->get('changeJob');
   $candi->readyTime=  $request->get('readyTime');
   $candi->mailstatus=  $request->get('mailstatus');
   $candi->contact     =  $request->get('contact');
   $candi->korean=  $request->get('korean');
   $candi->chinese=  $request->get('chinese');
   $candi->engeval=  $request->get('engeval');
   $candi->jpeval=  $request->get('jpeval');
   $candi->evaluation=  $request->get('evaluation');
   $candi->evalDate=  $request->get('evalDate');
   $candi->mobilestatus     =  $request->get('mobileStatus');
   $candi->foreigner =$foreigner;

   $candi->skill =$skill;
   $candi->col100=$occ[0];
   $candi->exp100=$occ[1];
   $candi->col39=$occ[2];
   $candi->exp39=$occ[3];
   $candi->majors              = $request->get('majors');
   $candi->wpselect              =  $workplace_select;
   $candi->save();
   return $this->showCandiByIdType($id,0,'Succes! Candidate was updateds!',null,null,null);


        //  $a=  $this->occupationArray($select_array,$candi->code,$txt_other);
 return $this->show($id);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    

    public function canSearch(Request $request)
    {

     $user = Auth::user();
     if (is_null($user) ) {
       return  redirect('/');
     }
     if(Input::get('csvBtn')){
      try {

        $this->downloadExcell($request);
      } catch (Exception $e) {


      }

    }
    DB::connection()->enableQueryLog();
          /////
    $graduatesMin=1995;
    $idjob=0;
    $items=Items::   orderBy('name', 'ASC')->get();
    $subitemsIT =    $items;
    $subitemsTech =  $items;
    $subitemsLab =   $items;
    $subitemsFin =   $items;
    $subitemsOther = $items;
    $majors=Major::orderBy('sort', 'ASC')->get();
    $university= DB::table('universities')
    ->orderBy( 'sort','ASC')
    ->get();
    $universityT=$universityN=$university;
    $users = DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();


    $province = Province2::orderBy('SortOrder', 'ASC')->get();
    $currAdd = Province2::orderBy('SortOrder', 'ASC')->get();
    $birthPlace=$province;
    $workplaceLst= $workplaceLstT= $workplaceLstN=$province;
    $can_gkind = DB::table('can_gkind') ->orderBy('sort', 'DESC')->get();
    $can_kind = DB::table('can_kind') ->orderBy('sort', 'ASC')->get();
          /////
    $foreigner=$request->input('foreigner');
    $other_admin=$request->input('other_admin');
    $other_man=$request->input('other_manu');

    $other_subLab=$request->input('other_subLab');
    $other_subIT=$request->input('other_subIT');
    $other_subTech=$request->input('other_subTech');
    $canNoFrom=$request->input('canNoFrom');
    $canNoTo=$request->input('canNoTo');
    $canEmail=$request->input('canEmail');
    $canPhone=$request->input('canPhone'); 
    $cansMarital=$request->input('married');
    $cansFist=$request->input('canFirstsrc');
    $cansMidle=$request->input('canMidlesrc');
    $cansLast=$request->input('canLastsrc');
    $canUni=$request->input('canUni');
    $canEmail=$request->input('canEmail');
    $canPhone=$request->input('canPhone');

    $canMajors=$request->input('canMajors');
         //$canGraduate=$request->input('canGraduate');
    $cangraduateMin=$request->input('graduateMin');
    $cangraduateMax=$request->input('graduateMax');
    $canSex=$request->input('canSex');
    $ageMin=$request->input('ageMin');
    $ageMax=$request->input('ageMax');
    $jLevel=$request->input('canJap');
    $jLevelTo=$request->input('canJapTo');
    $koreanS=$request->input('korean');
    $koreanSTo=$request->input('koreanTo');
    $chineseSTo=$request->input('chineseTo');
    $chineseS=$request->input('chinese');
    $engevalSTo=$request->input('engevalTo');
    $engevalS=$request->input('engeval');
    $jpevalSTo=$request->input('jpevalTo');
    $jpevalS=$request->input('jpeval');
         // if(empty($jLevel)){$jLevel=100;}
          // if(empty($jLevelTo)){$jLevelTo=200;}
    $eLevel=$request->input('canEng');
    $eLevelTo=$request->input('canEngTo');
                  // if(empty($eLevel)){$eLevel=0;}
                  //    if(empty($eLevel)){$eLevelTo=200;}
    $major=$request->input('canMajors');
    if(empty($ageMin)) {$ageMin=-1000;}
    if(empty($ageMax)) {$ageMax=2000;}
    $canBirth=$request->input('canBirth'); 
    $contact=$request->input('contact'); 
    $cancurrAdd=$request->input('cancurrAdd');
    $canWorkplace=$request->input('canWorkplace');
    $canWorkplaceTxt=$request->input('canWorkplaceTxt');
    $major_select=$request->input('major_select');
    $workplace_select=$request->input('workplace_select');
    $interviewDateFrom=$request->input('interviewDateFrom');
    $interviewDateTo=$request->input('interviewDateTo');
    $pic_s=$request->input('pic_s');

                // Search begin
    $itemsIT_select=$itemsTech_select=$subitemsLab_select =$subitemsFin_select
    =$subitemsOther_select=$coporations_select=$skill_select=array();
    if (!empty($request->input('itemsIT_select'))){ $itemsIT_select = $request->input('itemsIT_select');}  
    if (!empty($request->input('itemsTech_select'))){ $itemsTech_select = $request->input('itemsTech_select');}  
    if (!empty($request->input('subitemsLab_select'))){ $subitemsLab_select = $request->input('subitemsLab_select');}  
    if (!empty($request->input('subitemsFin_select'))){ $subitemsFin_select = $request->input('subitemsFin_select');}  
    if (!empty($request->input('subitemsOther_select'))){ $subitemsOther_select = $request->input('subitemsOther_select');}  
    if (!empty($request->input('skill_select'))){ $skill_select = $request->input('skill_select');} 
    $items_select=array_merge($itemsIT_select,$itemsTech_select,$subitemsLab_select ,$subitemsFin_select
     ,$subitemsOther_select);

    $counter = 0;


                // Search end
    $master=DB::table('master') ->orderBy('sort', 'DESC')->get();
    $english_levels=$master;
    $english_levels_To=$master;
    $japanese_levels=$master;
    $japanese_levels_To=$master;
    $korean=$chinese=$engeval=$jpeval=  $koreanTo=$chineseTo=$engevalTo=$jpevalTo=$master;
    $workplace =    $master;
    $sex =    $master;

    $request->flash();


          // $from = Carbon::today()->subYears($ageMin)->format('%y years, %m months and %d days');;

          //  $to = $minDate = Carbon::today()->subYears($ageMax)->format('%y years, %m months and %d days');
    $now1 = Carbon::now();
    $now2 = Carbon::now();
    $to= $now1->subYear($ageMin)->format('Y-m-d');
    $from= $now2->subYear($ageMax)->format('Y-m-d');
               ///// Search function

         $queryI = Candidates::query()->sortable();  //Initializes the query
         $queryI ->leftjoin('master as workp', 'candidates.workplace','=','workp.id')

         ->leftjoin('master as eng', 'candidates.eLevel','=','eng.id')   
         ->leftjoin('master as sex', 'candidates.sex','=','sex.id')             
         ->leftjoin('master as ja', 'candidates.jLevel','=','ja.id')
         ->leftjoin('master as sextab', 'candidates.sex', '=', 'sextab.id')
         ->leftjoin('province2 as birtP', 'candidates.birthPlace', '=', 'birtP.Id')
         ->leftjoin('province2 as curAdd', 'candidates.currentAdd', '=', 'curAdd.Id')
         ->leftjoin('major', 'candidates.majors','=','major.val')
         ->leftjoin('universities as universities', 'candidates.university'
          ,'=','universities.id')  
         ->leftjoin('master as source', 'candidates.source','=','source.id')
         ->select('candidates.*' ,
          DB::raw("concat(firstName, ' ',midleName,' ',lastName) as name"),
          'sextab.name as sexName' ,'eng.name as eLevelName' , 'ja.name as jLevelName','birtP.Name as birth_Place','workp.Name as wpName','curAdd.name as currentAdd','major.name as majorName','universities.eName as uName'
          ,'source.name as sourceName',   DB::raw("(CASE WHEN birth <'1500-01-01' THEN col1 ELSE birth END) AS birth2"))
         ->where(function ($query)use ($cangraduateMin,$cangraduateMax) {
          if(!empty($cangraduateMin) && !empty($cangraduateMax))
          {
            $query->whereBetween('graduates', [$cangraduateMin, $cangraduateMax]);
          } elseif (!empty($cangraduateMin) ) {
           $query->Where ('graduates','>=',$cangraduateMin );
         }elseif (!empty($cangraduateMax) ) {
           $query->Where ('graduates','<=',$cangraduateMax );
         }
       })
         ->where(function ($query)use ($cansMarital) {

           if (!is_null($cansMarital)) {
             $query->Where ('married',' =',$cansMarital );
           }

         })  
         ->where(function ($query)use ($cansFist) {
           if (!is_null($cansFist)) {
             $query->Where ('firstName','like','%' . $cansFist . '%' );
           } })
         ->where(function ($query)use ($cansMidle) {
          if (!is_null($cansMidle)) {
           $query->Where ('candidates.midleName','like','%' . $cansMidle . '%' );
         }
         })
         ->where(function ($query)use ($cansLast) {
           if (!is_null($cansLast)) {
           $query->Where ('candidates.lastName','like','%' . $cansLast . '%' );
         }

         })
         ->where(function ($query)use ($from,$to) {
           $query->whereBetween('birth', [$from, $to]);
         })
         ->where(function ($query)use ($jLevel,$jLevelTo) {
          if(!empty($jLevelTo) && !empty($jLevel))
          {   
           $query->whereBetween('jLevel', [ $jLevelTo,$jLevel]);
         } elseif (!empty($jLevelTo) ) {

           $query->Where ('jLevel','>=',$jLevelTo );
         }elseif (!empty($jLevel) ) {
           $query->Where ('jLevel','<=',$jLevel );
         }


       })
         ->where(function ($query)use ($eLevel,$eLevelTo) {
          if(!empty($eLevel) && !empty($eLevelTo))
          {
            $query->whereBetween('eLevel', [$eLevel, $eLevelTo]);
          } elseif (!empty($eLevel) ) {
           $query->Where ('eLevel','>=',$eLevel );
         }elseif (!empty($eLevelTo) ) {
           $query->Where ('eLevel','<=',$eLevelTo );
         }

       })

         ->where(function ($query)use ($koreanS,$koreanSTo) {
          if(!empty($koreanSTo) && !empty($koreanS))
          {   
           $query->whereBetween('korean', [$koreanS, $koreanSTo]);
         } elseif (!empty($koreanSTo) ) {
           $query->Where ('korean','>=',$koreanSTo );
         }elseif (!empty($koreanS) ) {
           $query->Where ('korean','<=',$koreanS );
         }               
       })
         ->where(function ($query)use ($chineseS,$chineseSTo) {
          if(!empty($chineseSTo) && !empty($chineseS))
          {   
           $query->whereBetween('chinese', [$chineseS, $chineseSTo]);
         } elseif (!empty($chineseSTo) ) {
           $query->Where ('chinese','>=',$chineseSTo );
         }elseif (!empty($chineseS) ) {
           $query->Where ('chinese','<=',$chineseS );
         }               
       })
         ->where(function ($query)use ($engevalS,$engevalSTo) {
          if(!empty($engevalSTo) && !empty($engevalS))
          {   
           $query->whereBetween('engeval', [ $engevalS,$engevalSTo]);
         } elseif (!empty($engevalSTo) ) {
           $query->Where ('engeval','>=',$engevalSTo );
         }elseif (!empty($engevalS) ) {
           $query->Where ('engeval','<=',$engevalS );
         }               
       })
         ->where(function ($query)use ($jpevalS,$jpevalSTo) {
          if(!empty($jpevalSTo) && !empty($jpevalS))
          {   
           $query->whereBetween('jpeval', [ $jpevalS,$jpevalSTo]);
         } elseif (!empty($jpevalSTo) ) {
           $query->Where ('jpeval','>=',$jpevalSTo );
         }elseif (!empty($jpevalS) ) {
           $query->Where ('jpeval','<=',$jpevalS );
         }               
       })
         ->where(function ($query)use ($canUni) {
           if (!is_null($canUni)) {
             $query->where('candidates.university','like','%' . $canUni . '%' );
           } })
         ->where(function ($query)use ($canSex) {
           if (!is_null($canSex)) {
            $query->where('candidates.sex','like','%' . $canSex . '%' );
          } })
         ->where(function ($query)use ($major) {
           if (!is_null($major)) {
             $query->where ('candidates.majors','like','%' . $major . '%' );
           }})

         ->where(function ($query)use ($canWorkplace) {
          if (!is_null($canWorkplace) && ($canWorkplace==CommonValue::MASTER_WORKPLACE_JAPAN
            ||$canWorkplace==CommonValue::MASTER_WORKPLACE_VIETNAM)) {
            $query->orwhere ('candidates.workPlace','like','%' . $canWorkplace . '%' );
            $query->orwhere ('candidates.workPlace','like','%' . CommonValue::MASTER_WORKPLACE_BOTH . '%' );
          }
          else if (!is_null($canWorkplace))  {
           $query->where ('candidates.workPlace','like','%' . $canWorkplace . '%' );
         } })
         ->where(function ($query)use ($canWorkplaceTxt) {
          if (!is_null($canWorkplaceTxt)) {
            $query->where  ('candidates.workplaceTxt','like','%' . $canWorkplaceTxt . '%' );
          }})

       // ->where(function ($query)use ($canEmail) {
       //           $query->whereNull('candidates.email')
       //        ->orWhere ('candidates.email','like','%' . $canEmail . '%' );
       //              })
         ->where(function ($query)use ($canPhone) {
          if (!is_null($canPhone)) {
           $query->where ('candidates.mobile','like','%' . $canPhone . '%' );
         }});
         if (!is_null($canNoFrom)) {
          $queryI->whereRaw('CAST(candidates.code AS UNSIGNED) >='.$canNoFrom);
        }
        if (!is_null($canNoTo)) {
          $queryI->whereRaw('CAST(candidates.code AS UNSIGNED) <='.$canNoTo);
        }
        if (!is_null($canEmail)) {
         $queryI->whereRaw("candidates.email like '%".$canEmail."%'");
       }
       if (!is_null($canBirth)) {
        $queryI->where("candidates.birthPlace","=",$canBirth);
      }
      if (!is_null($cancurrAdd)) {
        $queryI->where("candidates.currentAdd","=",$cancurrAdd);
      }
      if (!is_null($foreigner)) {
        $queryI->where("candidates.foreigner","=",$foreigner);
      }
      if (!is_null($interviewDateFrom)) {
        $queryI->where("candidates.mandanDate",">=",$interviewDateFrom);
      }
      if (!is_null($interviewDateTo)) {
        $queryI->where("candidates.mandanDate","<=",$interviewDateTo);
      }
      if (!is_null($pic_s)) {
        $queryI->where("candidates.staff","=",$pic_s);
      }
          // contack
      if (is_null($contact)) {
        $queryI->where(function ($query)use ($contact) {

         $query ->wherenull("candidates.contact")
         ->orwhere("candidates.contact","=",150);


       }); 

      } else
      {


      }
         // major and workplace
      if (!is_null($major_select) ) {
       $queryI->where(function ($query)use ($major_select) {
        foreach($major_select AS $value){
          $query->orWhere(DB::raw("majors"), 'LIKE', '%'.$value.'%'); 
        }
      });
     }
     if (!is_null($workplace_select) ) {
      $queryI->where(function ($query)use ($workplace_select) {
        foreach($workplace_select AS $value){
          $query->orWhere(DB::raw("wpselect"), 'LIKE', '%'.$value.'%'); 
        }
      });  
    }
    if (!is_null($skill_select) ) {
      $queryI->where(function ($query)use ($skill_select) {
        foreach($skill_select AS $value){
          $query->orWhere(DB::raw("skill"), 'LIKE', '%'.$value.'%'); 
        }
      });  
    }


        // Search Occupation
    $queryI->where(function ($query)use ($items_select,$other_subIT,$other_subTech,
     $other_subLab,$other_admin,$other_man) {
      if (!is_null($other_admin)) {

        $query->orWhere(DB::raw("otherAdmin"), 'LIKE', '%'.$other_admin.'%'); 
      }
      if (!is_null($other_man)) {

        $query->orWhere(DB::raw("otherManu"), 'LIKE', '%'.$other_man.'%'); 
      }
      if (!is_null($other_subIT)) {

        $query->orWhere(DB::raw("otherIT"), 'LIKE', '%'.$other_subIT.'%'); 
      }
      if (!is_null($other_subTech)) {
        $query->orWhere ('candidates.otherTech','like','%' . $other_subTech . '%' );
      }
      if (!is_null($other_subLab)) {
        $query->orWhere ('candidates.other','like','%' . $other_subLab . '%' );
      }
      foreach($items_select AS $value){

        $query->orWhere(DB::raw("col100"), 'LIKE', '%'.$value.'%'); 
        $query->orWhere(DB::raw("exp100"), 'LIKE', '%'.$value.'%'); 
      }


    });  
               // Search Exp 
    if(Input::get('csvBtn')){
      try {
        $candi=$queryI->orderBy('code', 'ASC')
        ->sortable() ->get();




      } catch (Exception $e) {
        dd($e);

        
      }

    } else {
      $candi=$queryI->orderBy('code', 'ASC')
      ->sortable() 

      ->paginate(50);

    }




    $canCount=$queryI->orderBy('code', 'ASC')
    ->count() ;


          // $query = DB::getQueryLog();
          //     $lastQuery = end($query);
          //  dd(  $lastQuery);

               //  create session for back function
    $links = array();
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssCandidate', $links);
            Session::flash('ssCandiCount', $canCount); 
          //  $request->session()->flash('ssCandiCount', $canCount);
          //  $request->session()->put('ssCandiCount', $canCount);
            // end create session

            if(Input::get('searchBtn')){
              return view('candidate.searchCan', compact('candi','english_levels'
                ,'japanese_levels','canSex','subitemsIT','subitemsTech','subitemsLab'
                ,'subitemsFin','subitemsOther','majors','university','japanese_levels_To'
                ,'english_levels_To','canCount','workplaceLst','universityT','universityN'
                ,'workplaceLstT','workplaceLstN','can_gkind','can_kind','users'
                ,'birthPlace','workplace','sex','currAdd','idjob'
                ,'korean','chinese','engeval','jpeval','koreanTo','chineseTo','engevalTo','jpevalTo'));
            }
            if(Input::get('csvBtn')){
              try {

                $columTitle = CommonFunction::genColumnExpCsvCanSearch();
                $fileName   = 'Candidates';

                $dataExcell       = CommonFunction::genDataExCandiSearch($columTitle, $candi);
                return  Excel::create($fileName, function($excel) use ($dataExcell) {
                  $excel->sheet('detail', function($sheet) use ($dataExcell) {
                    $sheet->fromArray($dataExcell);
                  });
                })->download("xlsx");
              } catch (Exception $e) {


              }

            }
            else  return view('candidate.searchCan', compact('candi','english_levels'
              ,'japanese_levels','canSex','subitemsIT','subitemsTech','subitemsLab'
              ,'subitemsFin','subitemsOther','majors','university','japanese_levels_To'
              ,'english_levels_To','canCount','workplaceLst','universityT','universityN'
              ,'workplaceLstT','workplaceLstN','can_gkind','can_kind','users','idjob'
              ,'birthPlace','workplace','sex','currAdd','korean','chinese','engeval','jpeval','koreanTo','chineseTo','engevalTo','jpevalTo'));

   // cansearchEnd!!!!!!!!

          }   

        public function canSearch1(Request $request)
        {
          DB::connection()->enableQueryLog();
          $user = Auth::user();
          if (is_null($user) ) {
           return  redirect('/');
         }
          /////
         $graduatesMin=0;
         $items=Items::   orderBy('name', 'ASC')->get();
         $subitemsIT =    $items;
         $subitemsTech =  $items;
         $subitemsLab =   $items;
         $subitemsFin =   $items;
         $subitemsOther = $items;
         $majors=Major::orderBy('sort', 'ASC')->get();

         $university= DB::table('universities')
         ->orderBy( 'sort','ASC')
         ->get();
         $universityT=$universityN=$university;
         $users = DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();
         $province = Province2::orderBy('SortOrder', 'ASC')->get();
         $currAdd = Province2::orderBy('SortOrder', 'ASC')->get();
         $birthPlace=$province;
         $workplaceLst= $workplaceLstT= $workplaceLstN=$province;
         $can_gkind = DB::table('can_gkind') ->orderBy('sort', 'DESC')->get();
         $can_kind = DB::table('can_kind') ->orderBy('sort', 'ASC')->get();
          /////
         $master=DB::table('master') ->orderBy('sort', 'DESC')->get();
         $korean=$chinese=$engeval=$jpeval=  $koreanTo=$chineseTo=$engevalTo=$jpevalTo=$master;
         $english_levels=$master;
         $japanese_levels=$master;
         $english_levels_To=$master;
         $japanese_levels_To=$master;
         $workplace =    $master;
         $sex =    $master;
         $request->flash();
         $birthStone='1950-01-01';

               ///// Search function

         $queryI = Candidates::query()->sortable();  //Initializes the query
         $queryI ->leftjoin('master as workp', 'candidates.workplace','=','workp.id')

         ->leftjoin('master as eng', 'candidates.eLevel','=','eng.id')   
         ->leftjoin('master as sex', 'candidates.sex','=','sex.id')             
         ->leftjoin('master as ja', 'candidates.jLevel','=','ja.id')
         ->leftjoin('master as sextab', 'candidates.sex', '=', 'sextab.id')
         ->leftjoin('province2 as birtP', 'candidates.birthPlace', '=', 'birtP.Id')
         ->leftjoin('province2 as curAdd', 'candidates.currentAdd', '=', 'curAdd.Id')
         ->leftjoin('major', 'candidates.majors','=','major.val')
         ->leftjoin('universities as universities', 'candidates.university'
          ,'=','universities.id')  
         ->leftjoin('master as source', 'candidates.source','=','source.id')
         ->whereRaw ('contact is null')
         ->select('candidates.*' ,'sextab.name as sexName' ,'eng.name as eLevelName' , 'ja.name as jLevelName','birtP.Name as birth_Place','workp.Name as wpName','curAdd.name as currentAdd','major.name as majorName','universities.eName as uName'
          ,'source.name as sourceName',
          DB::raw("(CASE WHEN birth <'1500-01-01' THEN col1 ELSE birth END) AS birth2")

        );


         $candi=$queryI->whereNull('candidates.contact' )
         ->orwhere("candidates.contact","=",150)
         ->orderBy('code', 'ASC')
         ->sortable() 

         ->paginate(50);
         $canCount=$queryI->orderBy('code', 'ASC')
         ->count() ;
                        // $query = DB::getQueryLog();
          //     $lastQuery = end($query);
          //  dd(  $lastQuery);
           //  create session for back function
         $links = array();
            $currentLink = URL::full(); // Getting current URI like 'category/books/'



             //Session::flash('backUrl', $links);
               array_push($links, $currentLink); // Putting it in the beginning of links array
               Session::flash('ssCandidate', $links);

             //Session::flash('backUrl', $links);
           // Session::flash('message', $canCount);

               Session::flash('ssCandiCount', $canCount); 
            // end create session
               return view('candidate.searchCan', compact('candi','english_levels'
                ,'japanese_levels','canSex','subitemsIT','subitemsTech','subitemsLab'
                ,'subitemsFin','subitemsOther','majors','university','japanese_levels_To'
                ,'universityN','universityT','workplaceLstT','workplaceLstN'
                ,'can_kind','can_gkind','users'
                ,'birthPlace','workplace','sex','currAdd','english_levels_To','canCount','workplaceLst','idjob'
                ,'korean','chinese','engeval','jpeval','koreanTo','chineseTo','engevalTo','jpevalTo'));
             }   
             public static function findCandiByID($id)
             {
              $candi = DB::table('candidates')
              ->leftjoin('master as workp', 'candidates.workplace','=','workp.id')
              ->leftjoin('users as users', 'candidates.staff','=','users.id')
              ->leftjoin('master as sex', 'candidates.sex','=','sex.id')  
              ->leftjoin('master as mobilestatus', 'candidates.mobilestatus','=','mobilestatus.id')   
              ->leftjoin('master as emailStatus', 'candidates.contact','=','emailStatus.id')   
              ->leftjoin('master as eng', 'candidates.eLevel','=','eng.id')            
              ->leftjoin('master as ja', 'candidates.jLevel','=','ja.id')
              ->leftjoin('master as korean', 'candidates.korean','=','korean.id')      
              ->leftjoin('master as chinese', 'candidates.chinese','=','chinese.id')      
              ->leftjoin('master as engeval', 'candidates.engeval','=','engeval.id')      
              ->leftjoin('master as jpeval', 'candidates.jpeval','=','jpeval.id')      
              ->leftjoin('province2 as birtP', 'candidates.birthPlace', '=', 'birtP.Id')
              ->leftjoin('province2 as curAdd', 'candidates.currentAdd', '=', 'curAdd.Id')
              ->leftjoin('master as source', 'candidates.source','=','source.id')
              ->leftjoin('universities as universities', 'candidates.university'

                ,'=','universities.id')  
              ->leftjoin('major', 'candidates.majors','=','major.val')
              ->leftjoin('follow_candidate', 'candidates.id','=','follow_candidate.candidates')
              ->select('candidates.*','follow_candidate.*', 'sex.name as sexName','eng.name as eLevelName' , 'ja.name as jLevelName','birtP.Name as birth_Place','workp.Name as wpName','curAdd.name as currentAdd','source.name as sourceName','universities.eName as uName','users.name'
                ,'major.name as majorName','mobilestatus.name as mbsName','emailStatus.name as emsName','korean.name as kname','chinese.name as chname','engeval.name as engevalname','jpeval.name as jpevalname')
              ->where("candidates.id","=", $id)
              ->first();
              return  $candi ;
            }
            public static function findOCbycode($occupation)
            {

              $itemsIT_select2          =array();  
              if (isset($occupation)) {
               $itemsIT_select2= explode(';', str_replace(';;', ';', $occupation->col100) );
             }
             return $itemsIT_select2;
           }
           public static function findEXPbycode($occupation)
           {

            $itemsIT_select2          =array();  
            if (isset($occupation)) {
             $itemsIT_select2= explode(';', str_replace(';;', ';', $occupation->exp100) );
           }
           return $itemsIT_select2;

         }
         public function destroy($id)
         {
           $user = Auth::user();
           if (is_null($user) ) {
             return  redirect('/');
           }
           $candidates = Candidates::find($id);
           $candidates->delete();
           return redirect('/canSearch');
         }
    // Function 
         public function requestArray(Request $request)
         {
           $itemsIT_select=$itemsTech_select=$itemOther_select=array();
           if (!empty($request->input('itemsIT_select'))){ $itemsIT_select = $request->input('itemsIT_select');}  
           if (!empty($request->input('itemsTech_select'))){ $itemsTech_select = $request->input('itemsTech_select');}  
           if (!empty($request->input('itemOther_select'))){ $itemOther_select = $request->input('itemOther_select');}  
           $items_select=array_merge($itemsIT_select,$itemsTech_select
             ,$itemOther_select);
           return $items_select;
         }
         public function occupationArray($items_select)
         {

          $colArray=array();
          $items=Items::all();
          $occupation_strList='';
          $occupationName_strList='';
          $exp_strList='';
          $expName_strList='';
          foreach($items_select as $coporation) {  
            if($coporation < 2000)
            {
              $exp_strList  .= $coporation.";";
            } else {
              $occupation_strList .= $coporation.";";
            }
            
            
          }
          foreach ($items as $item) {
           if(in_array($item->val,$items_select)) 
           {
             $occupationName_strList .= $item->name.";";
           }
           if(in_array($item->exp,$items_select)) 
           {
             $expName_strList .= $item->name.";";
           }

         }
         $occupation_strList = rtrim($occupation_strList, "; ");
         $exp_strList = rtrim($exp_strList, "; ");

         $occupationName_strList = rtrim($occupationName_strList, "; ");
         $expName_strList = rtrim($expName_strList, "; ");

         array_push( $colArray,$occupation_strList);
         array_push( $colArray,$exp_strList);
         array_push( $colArray,$occupationName_strList);
         array_push( $colArray,$expName_strList);
         return $colArray;


       }
       // 
       public function validateUDPMail($request,$id)
       {
        if (!is_null($request->email)) {
         $this->validate($request,[
           'email' => 'unique:candidates,email,'.$id,
         ],[
          'Email was duplicated'
        ]);
       }

     }
     public function validateInputMail($request)
     {
      if (!is_null($request->input('email'))) {
       $this->validate($request,[
         'email' => 'unique:candidates,email',
       ],[
        'Email was duplicated'
      ]);
     }

   }
   public function validateCandi($request)
   {
            //Validate
     $this->validate($request,[
      'firstName' => 'required',
      'lastName' => 'required',
      'university' => 'required',


                  //  'workPlace'=> 'required',
      'sex' => 'required',



    ],[
      'name.required' => ' The Client name field is required.'

    ]);
     $mandan=$request->input('mandan');
     $workcheck=$request->input('workcheck');
     if(!is_null($mandan)){
      $this->validate($request,[
        'mandanDate' => 'required',            
      ],[
        'Pre-Interview field is required.'
      ]);
    }
    if(!is_null($workcheck)){
      $this->validate($request,[
        'workDate' => 'required',   
        'mandanDate' => 'required',  
        'mandan' => 'required',  
        'pic_s' => 'required',  


      ],[
        'field: Pre-Interview date,Pre-Interview,Entering date,Staff Pre-interview is required.'
      ]);
    }



  }
  public function canSearch2(Request $request)
  {
    $jLevel=15;
    $items=Items::all();
    $subitemsIT =    $items;
    $subitemsTech =  $items;
    $subitemsLab =   $items;
    $subitemsFin =   $items;
    $subitemsOther = $items;
    $itemsIT_select=$itemsTech_select=$subitemsLab_select =$subitemsFin_select
    =$subitemsOther_select=$coporations_select=array();
    if (!empty($request->input('itemsIT_select'))){ $itemsIT_select = $request->input('itemsIT_select');}  
    if (!empty($request->input('itemsTech_select'))){ $itemsTech_select = $request->input('itemsTech_select');}  
    if (!empty($request->input('subitemsLab_select'))){ $subitemsLab_select = $request->input('subitemsLab_select');}  
    if (!empty($request->input('subitemsFin_select'))){ $subitemsFin_select = $request->input('subitemsFin_select');}  
    if (!empty($request->input('subitemsOther_select'))){ $subitemsOther_select = $request->input('subitemsOther_select');}  
    $items_select=array_merge($itemsIT_select,$itemsTech_select,$subitemsLab_select ,$subitemsFin_select
     ,$subitemsOther_select);
    $items_select=array("11", "12", "14");

    $counter = 0;
    $queryI = Candidates::query();
    $queryI->leftjoin('master as workp', 'candidates.workplace','=','workp.id')
    ->leftjoin('master as eng', 'candidates.jLevel','=','eng.id')   
    ->leftjoin('master as sex', 'candidates.sex','=','sex.id')             
    ->leftjoin('master as ja', 'candidates.eLevel','=','ja.id')
    ->leftjoin('master as sextab', 'candidates.sex', '=', 'sextab.id')
    ->leftjoin('province2 as birtP', 'candidates.birthPlace', '=', 'birtP.Id')
    ->leftjoin('province2 as curAdd', 'candidates.currentAdd', '=', 'curAdd.Id')
    ->leftjoin('major', 'candidates.majors','=','major.val')
    ->leftjoin('universities as universities', 'candidates.university'
      ,'=','universities.id')  
    ->leftjoin('master as source', 'candidates.source','=','source.id')
    ->leftjoin('occupation as oc', 'candidates.code','=','oc.id')
    ->select('candidates.*' ,'sextab.name as sexName' ,'eng.name as eLevelName','toeic' , 'ja.name as eLevelName','birtP.Name as birth_Place','workp.Name as wpName','curAdd.name as currentAdd','major.name as majorName','universities.jName as uName'
      ,'source.name as sourceName')
    ->where(function ($query)use ($jLevel) {
     $query->whereNull('candidates.jLevel')
     ->orWhere ('candidates.jLevel','<=',(int) $jLevel );
   });

    foreach($items_select AS $value){
      if($counter == 0){
        $queryI->where(DB::raw("col100"), 'LIKE', '%'.$value.'%'); 
      } else {
        $queryI->orWhere(DB::raw("col100"), 'LIKE', '%'.$value.'%'); 
      }
      $counter++;
    }
    $faqs = $queryI->get();
    $arrayCode=CommonFunction::getArraycodes($faqs ); 


  }
  public function spilitArray($items_select)
  {
    $colArray=array();
    $resultArray='';
    if(!is_null($items_select))
      { foreach($items_select as $coporation) { 
        $resultArray .= $coporation.";";
      }
    }
    return  $resultArray;
  }
  public function kindArray()
  {
   return array("","kind1","kind2","kind3","kind4","kind5","kind6","kind7","kind8","kind9","kind10"
    ,"kind11","kind12","kind13","kind14","kind15","kind16","kind17","kind18","kind19","kind20" );
 }   
 public function kindGArray()
 {
   return array("","gkind1","gkind2","gkind3","gkind4","gkind5","gkind6","gkind7","gkind8","gkind9"
    ,"gkind10","gkind11","gkind12","gkind13","gkind14","gkind15","gkind16","gkind17","gkind18"
    ,"gkind19","gkind20" );
 }
 public function requestKindArray(Request $request)
 {
   $strResult="";
   $kindArray=$this->kindArray();
   foreach ($kindArray as $key => $value) {
    if (!empty($request->input( $value ))) {
      $strResult.=$request->input( $value ) .";";

    } 
  }
  $strResult= str_replace("null;","", $strResult);
  $arrResult=  explode(";", $strResult)  ; sort( $arrResult); 

  if(count(array_unique($arrResult))<count($arrResult))
  {
    $this->validate($request,[
     'email' => 'digits_between:2,5',
   ],[
    'Skill was duplicated'
  ]);
  }

  return $strResult;
}
public static function stringSkill($skillChar)
{
  $strskill="";
  $listSkill=trim($skillChar, ';');
  $tblSkill=DB::table('can_kind') 
  ->whereIn('code', explode(';' ,$listSkill))
  ->get();
  return $tblSkill;


}
public function downloadExcell( Request $request )
{
 DB::connection()->enableQueryLog();
          /////
 $graduatesMin=1995;
 $items=Items::   orderBy('name', 'ASC')->get();
 $subitemsIT =    $items;
 $subitemsTech =  $items;
 $subitemsLab =   $items;
 $subitemsFin =   $items;
 $subitemsOther = $items;
 $majors=Major::orderBy('sort', 'ASC')->get();
 $university= DB::table('universities')
 ->orderBy( 'sort','ASC')
 ->get();
 $universityT=$universityN=$university;
 $users = DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();


 $province = Province2::orderBy('SortOrder', 'ASC')->get();
 $currAdd = Province2::orderBy('SortOrder', 'ASC')->get();
 $birthPlace=$province;
 $workplaceLst= $workplaceLstT= $workplaceLstN=$province;
 $can_gkind = DB::table('can_gkind') ->orderBy('sort', 'DESC')->get();
 $can_kind = DB::table('can_kind') ->orderBy('sort', 'ASC')->get();
          /////
 $foreigner=$request->input('foreigner');
 $other_admin=$request->input('other_admin');
 $other_man=$request->input('other_manu');

 $other_subLab=$request->input('other_subLab');
 $other_subIT=$request->input('other_subIT');
 $other_subTech=$request->input('other_subTech');
 $canNoFrom=$request->input('canNoFrom');
 $canNoTo=$request->input('canNoTo');
 $canEmail=$request->input('canEmail');
 $canPhone=$request->input('canPhone'); 
 $cansMarital=$request->input('married');
 $cansFist=$request->input('canFirstsrc');
 $cansMidle=$request->input('canMidlesrc');
 $cansLast=$request->input('canLastsrc');
 $canUni=$request->input('canUni');
 $canEmail=$request->input('canEmail');
 $canPhone=$request->input('canPhone');

 $canMajors=$request->input('canMajors');
         //$canGraduate=$request->input('canGraduate');
 $cangraduateMin=$request->input('graduateMin');
 $cangraduateMax=$request->input('graduateMax');
 $canSex=$request->input('canSex');
 $ageMin=$request->input('ageMin');
 $ageMax=$request->input('ageMax');
 $jLevel=$request->input('canJap');
 $jLevelTo=$request->input('canJapTo');
 $koreanS=$request->input('korean');
 $koreanSTo=$request->input('koreanTo');
 $chineseSTo=$request->input('chineseTo');
 $chineseS=$request->input('chinese');
 $engevalSTo=$request->input('engevalTo');
 $engevalS=$request->input('engeval');
 $jpevalSTo=$request->input('jpevalTo');
 $jpevalS=$request->input('jpeval');
         // if(empty($jLevel)){$jLevel=100;}
          // if(empty($jLevelTo)){$jLevelTo=200;}
 $eLevel=$request->input('canEng');
 $eLevelTo=$request->input('canEngTo');
                  // if(empty($eLevel)){$eLevel=0;}
                  //    if(empty($eLevel)){$eLevelTo=200;}
 $major=$request->input('canMajors');
 if(empty($ageMin)) {$ageMin=-1000;}
 if(empty($ageMax)) {$ageMax=2000;}
 $canBirth=$request->input('canBirth'); 
 $contact=$request->input('contact'); 
 $cancurrAdd=$request->input('cancurrAdd');
 $canWorkplace=$request->input('canWorkplace');
 $canWorkplaceTxt=$request->input('canWorkplaceTxt');
 $major_select=$request->input('major_select');
 $workplace_select=$request->input('workplace_select');
 $interviewDateFrom=$request->input('interviewDateFrom');
 $interviewDateTo=$request->input('interviewDateTo');
 $pic_s=$request->input('pic_s');

                // Search begin
 $itemsIT_select=$itemsTech_select=$subitemsLab_select =$subitemsFin_select
 =$subitemsOther_select=$coporations_select=$skill_select=array();
 if (!empty($request->input('itemsIT_select'))){ $itemsIT_select = $request->input('itemsIT_select');}  
 if (!empty($request->input('itemsTech_select'))){ $itemsTech_select = $request->input('itemsTech_select');}  
 if (!empty($request->input('subitemsLab_select'))){ $subitemsLab_select = $request->input('subitemsLab_select');}  
 if (!empty($request->input('subitemsFin_select'))){ $subitemsFin_select = $request->input('subitemsFin_select');}  
 if (!empty($request->input('subitemsOther_select'))){ $subitemsOther_select = $request->input('subitemsOther_select');}  
 if (!empty($request->input('skill_select'))){ $skill_select = $request->input('skill_select');} 
 $items_select=array_merge($itemsIT_select,$itemsTech_select,$subitemsLab_select ,$subitemsFin_select
   ,$subitemsOther_select);

 $counter = 0;


                // Search end
 $master=DB::table('master') ->orderBy('sort', 'DESC')->get();
 $english_levels=$master;
 $english_levels_To=$master;
 $japanese_levels=$master;
 $japanese_levels_To=$master;
 $workplace =    $master;
 $sex =    $master;
 $request->flash();


          // $from = Carbon::today()->subYears($ageMin)->format('%y years, %m months and %d days');;

          //  $to = $minDate = Carbon::today()->subYears($ageMax)->format('%y years, %m months and %d days');
 $now1 = Carbon::now();
 $now2 = Carbon::now();
 $to= $now1->subYear($ageMin)->format('Y-m-d');
 $from= $now2->subYear($ageMax)->format('Y-m-d');
               ///// Search function

         $queryI = Candidates::query()->sortable();  //Initializes the query
         $queryI ->leftjoin('master as workp', 'candidates.workplace','=','workp.id')

         ->leftjoin('master as eng', 'candidates.eLevel','=','eng.id')   
         ->leftjoin('master as sex', 'candidates.sex','=','sex.id')             
         ->leftjoin('master as ja', 'candidates.jLevel','=','ja.id')
         ->leftjoin('master as sextab', 'candidates.sex', '=', 'sextab.id')
         ->leftjoin('province2 as birtP', 'candidates.birthPlace', '=', 'birtP.Id')
         ->leftjoin('province2 as curAdd', 'candidates.currentAdd', '=', 'curAdd.Id')
         ->leftjoin('major', 'candidates.majors','=','major.val')
         ->leftjoin('universities as universities', 'candidates.university'
          ,'=','universities.id')  
         ->leftjoin('master as source', 'candidates.source','=','source.id')
         ->select('firstName','midleName','lastName','email','mobile','candidates.code',
          DB::raw("concat(firstName, ' ',midleName,' ',lastName) as name") )
         ->where(function ($query)use ($cangraduateMin,$cangraduateMax) {
          if(!empty($cangraduateMin) && !empty($cangraduateMax))
          {
            $query->whereBetween('graduates', [$cangraduateMin, $cangraduateMax]);
          } elseif (!empty($cangraduateMin) ) {
           $query->Where ('graduates','>=',$cangraduateMin );
         }elseif (!empty($cangraduateMax) ) {
           $query->Where ('graduates','<=',$cangraduateMax );
         }
       })
         ->where(function ($query)use ($cansMarital) {

           if (!is_null($cansMarital)) {
             $query->Where ('married',' =',$cansMarital );
           }

         })  
         ->where(function ($query)use ($cansFist) {
           if (!is_null($cansFist)) {
             $query->Where ('firstName','like','%' . $cansFist . '%' );
           } })
         ->where(function ($query)use ($cansMidle) {
           $query->Where ('candidates.midleName','like','%' . $cansMidle . '%' );
         })
         ->where(function ($query)use ($cansLast) {
           $query->Where ('candidates.lastName','like','%' . $cansLast . '%' );

         })
         ->where(function ($query)use ($from,$to) {
           $query->whereBetween('birth', [$from, $to]);
         })
         ->where(function ($query)use ($jLevel,$jLevelTo) {
          if(!empty($jLevelTo) && !empty($jLevel))
          {   
           $query->whereBetween('jLevel', [ $jLevelTo,$jLevel]);
         } elseif (!empty($jLevelTo) ) {

           $query->Where ('jLevel','>=',$jLevelTo );
         }elseif (!empty($jLevel) ) {
           $query->Where ('jLevel','<=',$jLevel );
         }


       })
         ->where(function ($query)use ($eLevel,$eLevelTo) {
          if(!empty($eLevel) && !empty($eLevelTo))
          {
            $query->whereBetween('eLevel', [$eLevel, $eLevelTo]);
          } elseif (!empty($eLevel) ) {
           $query->Where ('eLevel','>=',$eLevel );
         }elseif (!empty($eLevelTo) ) {
           $query->Where ('eLevel','<=',$eLevelTo );
         }

       })
         ->where(function ($query)use ($koreanS,$koreanSTo) {
          if(!empty($koreanSTo) && !empty($koreanS))
          {   
           $query->whereBetween('korean', [$koreanS, $koreanSTo]);
         } elseif (!empty($koreanSTo) ) {
           $query->Where ('korean','>=',$koreanSTo );
         }elseif (!empty($koreanS) ) {
           $query->Where ('korean','<=',$koreanS );
         }               
       })
         ->where(function ($query)use ($chineseS,$chineseSTo) {
          if(!empty($chineseSTo) && !empty($chineseS))
          {   
           $query->whereBetween('chinese', [$chineseS, $chineseSTo]);
         } elseif (!empty($chineseSTo) ) {
           $query->Where ('chinese','>=',$chineseSTo );
         }elseif (!empty($chineseS) ) {
           $query->Where ('chinese','<=',$chineseS );
         }               
       })
         ->where(function ($query)use ($engevalS,$engevalSTo) {
          if(!empty($engevalSTo) && !empty($engevalS))
          {   
           $query->whereBetween('engeval', [ $engevalS,$engevalSTo]);
         } elseif (!empty($engevalSTo) ) {
           $query->Where ('engeval','>=',$engevalSTo );
         }elseif (!empty($engevalS) ) {
           $query->Where ('engeval','<=',$engevalS );
         }               
       })
         ->where(function ($query)use ($jpevalS,$jpevalSTo) {
          if(!empty($jpevalSTo) && !empty($jpevalS))
          {   
           $query->whereBetween('jpeval', [ $jpevalS,$jpevalSTo]);
         } elseif (!empty($jpevalSTo) ) {
           $query->Where ('jpeval','>=',$jpevalSTo );
         }elseif (!empty($jpevalS) ) {
           $query->Where ('jpeval','<=',$jpevalS );
         }               
       })
         ->where(function ($query)use ($canUni) {
           if (!is_null($canUni)) {
             $query->where('candidates.university','like','%' . $canUni . '%' );
           } })
         ->where(function ($query)use ($canSex) {
           if (!is_null($canSex)) {
            $query->where('candidates.sex','like','%' . $canSex . '%' );
          } })
         ->where(function ($query)use ($major) {
           if (!is_null($major)) {
             $query->where ('candidates.majors','like','%' . $major . '%' );
           }})

         ->where(function ($query)use ($canWorkplace) {
          if (!is_null($canWorkplace) && ($canWorkplace==CommonValue::MASTER_WORKPLACE_JAPAN
            ||$canWorkplace==CommonValue::MASTER_WORKPLACE_VIETNAM)) {
            $query->orwhere ('candidates.workPlace','like','%' . $canWorkplace . '%' );
            $query->orwhere ('candidates.workPlace','like','%' . CommonValue::MASTER_WORKPLACE_BOTH . '%' );
          }
          else if (!is_null($canWorkplace))  {
           $query->where ('candidates.workPlace','like','%' . $canWorkplace . '%' );
         } })
         ->where(function ($query)use ($canWorkplaceTxt) {
          if (!is_null($canWorkplaceTxt)) {
            $query->where  ('candidates.workplaceTxt','like','%' . $canWorkplaceTxt . '%' );
          }})

       // ->where(function ($query)use ($canEmail) {
       //           $query->whereNull('candidates.email')
       //        ->orWhere ('candidates.email','like','%' . $canEmail . '%' );
       //              })
         ->where(function ($query)use ($canPhone) {
          if (!is_null($canPhone)) {
           $query->where ('candidates.mobile','like','%' . $canPhone . '%' );
         }});
         if (!is_null($canNoFrom)) {
          $queryI->whereRaw('CAST(candidates.code AS UNSIGNED) >='.$canNoFrom);
        }
        if (!is_null($canNoTo)) {
          $queryI->whereRaw('CAST(candidates.code AS UNSIGNED) <='.$canNoTo);
        }
        if (!is_null($canEmail)) {
         $queryI->whereRaw("candidates.email like '%".$canEmail."%'");
       }
       if (!is_null($canBirth)) {
        $queryI->where("candidates.birthPlace","=",$canBirth);
      }
      if (!is_null($cancurrAdd)) {
        $queryI->where("candidates.currentAdd","=",$cancurrAdd);
      }
      if (!is_null($foreigner)) {
        $queryI->where("candidates.foreigner","=",$foreigner);
      }
      if (!is_null($interviewDateFrom)) {
        $queryI->where("candidates.mandanDate",">=",$interviewDateFrom);
      }
      if (!is_null($interviewDateTo)) {
        $queryI->where("candidates.mandanDate","<=",$interviewDateTo);
      }
      if (!is_null($pic_s)) {
        $queryI->where("candidates.staff","=",$pic_s);
      }
          // contack
      if (is_null($contact)) {
        $queryI->where(function ($query)use ($contact) {

         $query ->wherenull("candidates.contact")
         ->orwhere("candidates.contact","=",150);


       }); 

      } else
      {


      }
         // major and workplace
      if (!is_null($major_select) ) {
       $queryI->where(function ($query)use ($major_select) {
        foreach($major_select AS $value){
          $query->orWhere(DB::raw("majors"), 'LIKE', '%'.$value.'%'); 
        }
      });
     }
     if (!is_null($workplace_select) ) {
      $queryI->where(function ($query)use ($workplace_select) {
        foreach($workplace_select AS $value){
          $query->orWhere(DB::raw("wpselect"), 'LIKE', '%'.$value.'%'); 
        }
      });  
    }
    if (!is_null($skill_select) ) {
      $queryI->where(function ($query)use ($skill_select) {
        foreach($skill_select AS $value){
          $query->orWhere(DB::raw("skill"), 'LIKE', '%'.$value.'%'); 
        }
      });  
    }


        // Search Occupation
    $queryI->where(function ($query)use ($items_select,$other_subIT,$other_subTech,
     $other_subLab,$other_admin,$other_man) {
      if (!is_null($other_admin)) {

        $query->orWhere(DB::raw("otherAdmin"), 'LIKE', '%'.$other_admin.'%'); 
      }
      if (!is_null($other_man)) {

        $query->orWhere(DB::raw("otherManu"), 'LIKE', '%'.$other_man.'%'); 
      }
      if (!is_null($other_subIT)) {

        $query->orWhere(DB::raw("otherIT"), 'LIKE', '%'.$other_subIT.'%'); 
      }
      if (!is_null($other_subTech)) {
        $query->orWhere ('candidates.otherTech','like','%' . $other_subTech . '%' );
      }
      if (!is_null($other_subLab)) {
        $query->orWhere ('candidates.other','like','%' . $other_subLab . '%' );
      }
      foreach($items_select AS $value){

        $query->orWhere(DB::raw("col100"), 'LIKE', '%'.$value.'%'); 
        $query->orWhere(DB::raw("exp100"), 'LIKE', '%'.$value.'%'); 
      }


    });  
               // Search Exp 
    if(Input::get('csvBtn')){
      try {
        $candi=$queryI->orderBy('code', 'ASC')
        ->sortable() ->get();




      } catch (Exception $e) {
        dd($e);

        
      }

    } else {
      $candi=$queryI->orderBy('code', 'ASC')
      ->sortable() 

      ->paginate(50);

    }




    $canCount=$queryI->orderBy('code', 'ASC')
    ->count() ;


          // $query = DB::getQueryLog();
          //     $lastQuery = end($query);
          //  dd(  $lastQuery);

               //  create session for back function
    $links = array();
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssCandidate', $links);
            Session::flash('ssCandiCount', $canCount); 
          //  $request->session()->flash('ssCandiCount', $canCount);
          //  $request->session()->put('ssCandiCount', $canCount);
            // end create session

            if(Input::get('searchBtn')){
              return view('candidate.searchCan', compact('candi','english_levels'
                ,'japanese_levels','canSex','subitemsIT','subitemsTech','subitemsLab'
                ,'subitemsFin','subitemsOther','majors','university','japanese_levels_To'
                ,'english_levels_To','canCount','workplaceLst','universityT','universityN'
                ,'workplaceLstT','workplaceLstN','can_gkind','can_kind','users'
                ,'birthPlace','workplace','sex','currAdd'));
            }
            if(Input::get('csvBtn')){
              try {

                $columTitle = CommonFunction::genColumnExpCsvCanSearch();
                $fileName   = 'Candidates';

                $dataExcell       = CommonFunction::genDataExCandiSearch($columTitle, $candi);
                return  Excel::create($fileName, function($excel) use ($dataExcell) {
                  $excel->sheet('detail', function($sheet) use ($dataExcell) {
                    $sheet->fromArray($dataExcell);
                  });
                })->download("xlsx");
              } catch (Exception $e) {


              }

            }
            else  return view('candidate.searchCan', compact('candi','english_levels'
              ,'japanese_levels','canSex','subitemsIT','subitemsTech','subitemsLab'
              ,'subitemsFin','subitemsOther','majors','university','japanese_levels_To'
              ,'english_levels_To','canCount','workplaceLst','universityT','universityN'
              ,'workplaceLstT','workplaceLstN','can_gkind','can_kind','users'
              ,'birthPlace','workplace','sex','currAdd'));

   // cansearchEnd!!!!!!!!
          }
        public static function  findEduByCanID($id)
        {
          $edu = DB::table('can_edu')->Where('idcan','=',$id)->orderBy('date')->get();
          return $edu;
        }  
        public static function findHisJobByCanID($id)
        {
          $hisjob = DB::table('can_his_job')
          ->leftjoin('items','items.id','=','can_his_job.idoc')
          ->select('can_his_job.*','items.name as occupation')

          ->Where('idcan','=',$id)
          ->orderBy('from')
          ->get();
          return $hisjob;
        }  


        public static function findactionlist($id)
        {
          $actionlist = DB::table('follow_candidate')
          
          // ->Where('follow_candidate.candidates','=',$id)
          // ->leftjoin('master AS A','A.id','=','follow_candidate.status')
          ->leftjoin('master AS B','B.id','=','follow_candidate.job_seeking_need')
          ->leftjoin('master AS C','C.id','=','follow_candidate.action')
          ->leftjoin('users', 'users.id', '=', 'follow_candidate.sekisho_pic')
          ->select('follow_candidate.*','users.name as name','B.name as job_seeking_need','C.name as action')
          ->Where('follow_candidate.candidates','=',$id)
          // ->Where('follow_candidate.candidates','=',$id)
         
          ->orderBy('date','desc')
          ->orderBy('time_start','desc')
          ->get();
          return $actionlist;
        } 
        
        
        public static function findactionlast($id)
        {
          $actionlist = DB::table('follow_candidate')
          
          // ->Where('follow_candidate.candidates','=',$id)
          // ->leftjoin('master AS A','A.id','=','follow_candidate.status')
          ->leftjoin('master AS B','B.id','=','follow_candidate.job_seeking_need')
          ->leftjoin('master AS C','C.id','=','follow_candidate.action')
          ->leftjoin('users', 'users.id', '=', 'follow_candidate.sekisho_pic')
          ->select('follow_candidate.*','users.name as name','B.name as job_seeking_need','C.name as action')
          ->Where('follow_candidate.candidates','=',$id)
          // ->Where('follow_candidate.candidates','=',$id)
         
          ->orderBy('date','desc')
          ->orderBy('time_start','desc')->first();
          // ->get();
          return $actionlist;
        }  

      }


