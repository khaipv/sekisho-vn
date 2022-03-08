<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Master;
use App\Division;
use DB;
use App\Can_edu;
use App\Can_his_job;;
use App\User;
use App\Items;
use App\Province2;
use App\Candidates;
use Session;
use App\Major;
use App\Http\Controllers\CandidateController;
use Illuminate\Support\Facades\Auth;
class HisjobController extends Controller
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
     $idjob=0;
     if (is_null($user) ) {
       return  redirect('/');
     }
     $fromcr=$request->get('fromcr');
     $tocr=$request->get('tocr');
      $occupationcr=$request->get('occupationcr');
       $companycr=$request->get('companycr');
        $positioncr=$request->get('positioncr');
         $detailcr=$request->get('detailcr');

      $idCandidate=$request->get('canID');
       if (  !is_null($tocr) && !$this->validateFromTo($fromcr,$tocr) )
       { 
        $this->validate($request,[
      'fromcr' => 'email',
    ],[
      ' Error!! From-Date <=To-Date'

    ]);
        }
     $hisjob= new Can_his_job
     ([
       'idcan'=>  $request->get('canID'),
       'idoc'=>  $request->get('occupationcr'),
       'company'=>  $request->get('companycr'),
       'position'=>  $request->get('positioncr'),
       'detail'=>  $request->get('detailcr'),
       'from'=>  $request->get('fromcr'),
       'to'=>  $request->get('tocr'),
     ]);
     
     $hisjob->save();
    

     $type=2;
     $message="susscess!! Candidate was updated";
         return view('job.result');

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
     
     $fromedit=$request->get('fromedit');
     $toedit=$request->get('toedit');
     $message="susscess!! Candidate was updated";
     $hisjobudp=Can_his_job::findOrFail($id);

       if (  !is_null($toedit) &&  $fromedit >$toedit )
       { 
        $this->validate($request,[
      'fromedit' => 'email',
    ],[
      ' Error!! From-Date <=To-Date'

    ]);
        }

     $hisjobudp->idoc= $request->get('occedit');
  
     $hisjobudp->company= $request->get('companyedit');
     $hisjobudp->position= $request->get('positionedit');
     $hisjobudp->detail= $request->get('detailedit');
     $hisjobudp->from= $request->get('fromedit');
     $hisjobudp->to= $request->get('toedit');
     $hisjobudp->save();
     $type=2;
     $message="susscess!! Candidate was updated";
       return view('job.result');


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
     $type=2;
     $message="susscess!! Candidate was updated";  
     $hisjobudp=Can_his_job::findOrFail($id);
     $canid=$hisjobudp->idcan;
     $hisjobudp->delete();
           return redirect()->back() ;

      // return back

   }
   public function showCan($id,$type,$message)
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
     $candi=$this -> findCandiByID($id);
     $occupation=$this ->findOCbycode($candi);
     $experience=$this ->findEXPbycode($candi);
     $educations=$this->findEduByCanID($candi->id);
     $queryI = Candidates::query()
     ->select('candidates.id',
      DB::raw("(CASE WHEN birth <'1500-01-01' THEN col1 ELSE birth END) AS birth2")

    )
     ->where("candidates.id","=", $id)
     ->first();

     $birth2=$queryI;
                // create session


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
        ,'skillList','orderlist','educations','type','message'
        ,'subitemsIT','subitemsTech','subitemsLab','subitemsFin','subitemsOther','experience'));

    }
    public function findCandiByID($id)
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
      ->select('candidates.*', 'sex.name as sexName','eng.name as eLevelName' , 'ja.name as jLevelName','birtP.Name as birth_Place','workp.Name as wpName','curAdd.name as currentAdd','source.name as sourceName','universities.eName as uName','users.name'
        ,'major.name as majorName','mobilestatus.name as mbsName','emailStatus.name as emsName','korean.name as kname','chinese.name as chname','engeval.name as engevalname','jpeval.name as jpevalname')
      ->where("candidates.id","=", $id)
      ->first();
      return  $candi ;
    }
    public function findOCbycode($occupation)
    {

      $itemsIT_select2          =array();  
      if (isset($occupation)) {
       $itemsIT_select2= explode(';', str_replace(';;', ';', $occupation->col100) );
     }
     return $itemsIT_select2;
   }
   public function findEXPbycode($occupation)
   {

    $itemsIT_select2          =array();  
    if (isset($occupation)) {
     $itemsIT_select2= explode(';', str_replace(';;', ';', $occupation->exp100) );
   }
   return $itemsIT_select2;

 }
 public function findEduByCanID($id)
 {
  $edu = DB::table('can_edu')->Where('idcan','=',$id)->get();
  return $edu;
}  
public function stringSkill($skillChar)
{
  $strskill="";
  $listSkill=trim($skillChar, ';');
  $tblSkill=DB::table('can_kind') 
  ->whereIn('code', explode(';' ,$listSkill))
  ->get();
  return $tblSkill;


}
    public function addnew(Request $request)
    {
        $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
            $items=Items::   orderBy('name', 'ASC')->get();
          $subitemsIT =    $items;
            $id=$request->get('canID');
            $candi=Candidates::findOrFail($id);
             return view('job.createjob',compact('id','candi','subitemsIT'));

     } 
            public function editJob( Request  $request)
    {
     $user = Auth::user();
     if (is_null($user) ) {
       return  redirect('/');
     }
      $job=Can_his_job::findOrFail($request->canID);
       $items=Items::   orderBy('name', 'ASC')->get();
          $subitemsIT =    $items;
      $candi=Candidates::findOrFail($job->idcan);
        return view('job.editjob',compact('candi','job','subitemsIT'));
     
   }
   public function validateFromTo($from,$to) {
    if ($from>$to) {

      return false;

    }
    return true;

   }

}