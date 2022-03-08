<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use Illuminate\Support\Facades\Auth;

use App\Client;
use App\Division;
use App\Dr_kind;
use App\Dr_report;
use App\Dr_master;
use App\Orders;
use Carbon\Carbon;

use Illuminate\Support\Facades\URL;
use Session;
class DailyReportController extends Controller
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
         $progressCode='progress';   
          $reportsQuery = Dr_report::sortable()
          ->leftjoin('users', 'dr_report.userID', '=', 'users.id')
          ->leftjoin('client', 'dr_report.companyID', '=', 'client.id')
          ->leftjoin('division', 'dr_report.divisionID', '=', 'division.id')
          ->select('dr_report.*', 'client.companyname','division.divisionname','users.name as userName')
          ->orderBy('date', 'desc');  
       $reports=$reportsQuery   ->paginate(10);

         $order = Orders::sortable()
            ->leftjoin('division', 'order.divisionID', '=', 'division.id')
            ->leftjoin('client', 'order.clientID', '=', 'client.id')
            ->leftjoin('users', 'order.pic_s', '=', 'users.id')
            ->leftJoin('master as status', 'order.status', '=', 'status.id')
          ->leftJoin('master as pro', function ( $join ) use  ($progressCode) {
            $join->on('order.progress', '=', 'pro.code')
                 ->where('pro.type','=',$progressCode);})
            ->leftjoin('master as type', 'order.type', '=', 'type.id')
            ->leftjoin('master as jp', 'order.JLevel', '=', 'jp.id')
            ->leftjoin('master as eng', 'order.ELevel', '=', 'eng.id')
            ->leftjoin('master as waranty', 'order.warranty', '=', 'waranty.id')
            ->leftjoin('units as units', 'order.unitFrom', '=', 'units.id')
            ->leftjoin('units as unitSaFrom', 'order.unitSaFrom', '=', 'unitSaFrom.id')
            ->select('order.*', 'division.divisionname as divisionName','client.companyname','users.name as pics','status.name as statusName','pro.name as progressName','type.name as typeName','jp.name as jpName','eng.name as engName','waranty.name as warantyName','pro.name as progressName','units.code as unitName'
          ,'unitSaFrom.code as unitSaName' )
         
            ->orderBy('id', 'desc')
             ->sortable()
            ->paginate(10);
               $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssReport', $links);

       return view('dr_report.loginReport', compact('reports','order'));
         
    }
     public function index2()
    {
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
              $division = Division::orderBy('divisionname', 'ASC') ->get();

         $client = Client::orderBy('companyname', 'ASC') ->get();
         $kind=Dr_kind::orderBy('id', 'ASC') ->get();
        return view('dr_report.createreport', compact('client','division','kind'));
      
         
    }
   
       public function reportSearch(Request $request)
    {
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
 $request->flash();
              $division = Division::orderBy('divisionname', 'ASC') ->get();

         $client = Client::orderBy('companyname', 'ASC') ->get();
         $kind=Dr_kind::orderBy('id', 'ASC') ->get();
        
         $from=$request->input('fromDate');
         $to=$request->input('toDate');
         $companysrc_searchrp=$request->input('companysrc_searchrp');
         $divisionsrc_searchrp=$request->input('divisionsrc_searchrp');
         $creater_searchrp=$request->input('pics');
          $reportsQuery = Dr_report::sortable()
          ->leftjoin('users', 'dr_report.userID', '=', 'users.id')
          ->leftjoin('client', 'dr_report.companyID', '=', 'client.id')
          ->leftjoin('division', 'dr_report.divisionID', '=', 'division.id')
          ->select('dr_report.*', 'client.companyname','division.divisionname','users.name as userName')
          ->orderBy('date', 'DESC');  
          if (!is_null($from)) {
          $reportsQuery ->where(function ($query)use ($from) {
           $query
                ->Where ('dr_report.date','>=', $from );
              });
        }
            if (!is_null($to)) {
          $reportsQuery ->where(function ($query)use ($to) {
           $query
                ->Where ('dr_report.date','<=', $to );
              });
        }
         if (!is_null($companysrc_searchrp)) {
          $reportsQuery ->where(function ($query)use ($companysrc_searchrp) {
           $query
                ->Where ('client.companyname','like', '%' . $companysrc_searchrp . '%' )
                ->orwhere('dr_report.other','like', '%' . $companysrc_searchrp . '%' );
              });
        }
           if (!is_null($divisionsrc_searchrp)) {
          $reportsQuery ->where(function ($query)use ($divisionsrc_searchrp) {
           $query
                ->Where ('division.divisionname','like', '%' . $divisionsrc_searchrp . '%' );
              });
        }
           if (!is_null($creater_searchrp)) {
          $reportsQuery ->where(function ($query)use ($creater_searchrp) {
           $query
                ->Where ('dr_report.userID','=',   $creater_searchrp   );
              });
        }
        $reports=$reportsQuery   ->paginate(10);
         $users=DB::table('users')->where('status','>=','1')->orderBy('sort', 'ASC')->get();
                   $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssReport', $links);
        return view('dr_report.searchReport', compact('reports','division','kind','client','users'));
      
         
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

         $division = Division::orderBy('divisionname', 'ASC') ->get();

         $client = Client::orderBy('companyname', 'ASC') ->get();
         $kind=Dr_kind::orderBy('id', 'ASC') ->get();
         $dr_master=Dr_master::orderBy('sort', 'ASC') ->get();
        return view('dr_report.createreport', compact('client','division','kind','dr_master'));
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
      
          $this->validateOT($request->get('fromTime'),$request->get('toTime'),$request);
     
            $newDr_report = new Dr_report([
        'date'=> $request->get('date'),
        'divisionID'=> $request->get('division'),
        'companyID'=> $request->get('client'),
        'from' =>Carbon::parse($request->get('fromTime')),
        'to' =>Carbon::parse($request->get('toTime')),
        'other' => $request->get('other'),
        'customer'=> $request->get('customer'),
        'visitor'=> $request->get('visitor'),
        'kind'=> $request->get('kind'),
        'title'=> $request->get('title'),
        'detail'=> $request->get('detail'),
        'way'=> $request->get('way'),
        'userID' =>$user->id
          
        ]);

             $newDr_report->save();
            return $this->show($newDr_report->id);
    
    }

    /**
     * Display the specified resource.
     *Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         $reportsQuery = Dr_report::sortable()
          ->leftjoin('users', 'dr_report.userID', '=', 'users.id')
          ->leftjoin('client', 'dr_report.companyID', '=', 'client.id')
          ->leftjoin('division', 'dr_report.divisionID', '=', 'division.id')
          ->leftjoin('dr_kind', 'dr_report.kind', '=', 'dr_kind.id')
          ->leftjoin('dr_master as way', 'dr_report.way', '=', 'way.id')
          ->where('dr_report.id','=',$id)
          ->select('dr_report.*', 'client.companyname','division.divisionname'
            ,'users.name as userName','way.name as wayname','dr_kind.name as kindname')
          ->orderBy('date', 'ASC');  
         $reports=$reportsQuery   ->first();
         $division = Division::orderBy('divisionname', 'ASC') ->get();

         $client = Client::orderBy('companyname', 'ASC') ->get();
         $kind=Dr_kind::orderBy('id', 'ASC') ->get();
         $dr_master=Dr_master::orderBy('sort', 'ASC') ->get();
          if (Session::has('ssReport')) {
                   $url = Session::get('ssReport');
                   $currentLink = URL::full(); 
                    array_push($url, $currentLink); 
                    Session::flash('ssReport', $url);
                    }
                    $upd=0;
        return view('dr_report.detailreport', compact('client','division','kind','dr_master','reports',
          'upd'));
     
      
    }
     public function show2($id)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         $reportsQuery = Dr_report::sortable()
          ->leftjoin('users', 'dr_report.userID', '=', 'users.id')
          ->leftjoin('client', 'dr_report.companyID', '=', 'client.id')
          ->leftjoin('division', 'dr_report.divisionID', '=', 'division.id')
          ->leftjoin('dr_kind', 'dr_report.kind', '=', 'dr_kind.id')
          ->leftjoin('dr_master as way', 'dr_report.way', '=', 'way.id')
          ->where('dr_report.id','=',$id)
          ->select('dr_report.*', 'client.companyname','division.divisionname'
            ,'users.name as userName','way.name as wayname','dr_kind.name as kindname')
          ->orderBy('id', 'ASC');  
         $reports=$reportsQuery   ->first();
         $division = Division::orderBy('divisionname', 'ASC') ->get();

         $client = Client::orderBy('companyname', 'ASC') ->get();
         $kind=Dr_kind::orderBy('id', 'ASC') ->get();
         $dr_master=Dr_master::orderBy('sort', 'ASC') ->get();
          if (Session::has('ssReport')) {
                   $url = Session::get('ssReport');
                   $currentLink = URL::full(); 
                    array_push($url, $currentLink); 
                    Session::flash('ssReport', $url);
                    }
            $upd=1;        
        return view('dr_report.detailreport', compact('client','division','kind','dr_master','reports'
          ,'upd'))->with('message', 'Succes! Report was updated');
     
      
    }
    public function showudp($id)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         $reportsQuery = Dr_report::sortable()
          ->leftjoin('users', 'dr_report.userID', '=', 'users.id')
          ->leftjoin('client', 'dr_report.companyID', '=', 'client.id')
          ->leftjoin('division', 'dr_report.divisionID', '=', 'division.id')
          ->leftjoin('dr_kind', 'dr_report.kind', '=', 'dr_kind.id')
          ->leftjoin('dr_master as way', 'dr_report.way', '=', 'way.id')
          ->where('dr_report.id','=',$id)
          ->select('dr_report.*', 'client.companyname','division.divisionname'
            ,'users.name as userName','way.name as wayname','dr_kind.name as kindname')
          ->orderBy('id', 'ASC');  
         $reports=$reportsQuery   ->first();
         $division = Division::orderBy('divisionname', 'ASC') ->get();

         $client = Client::orderBy('companyname', 'ASC') ->get();
         $kind=Dr_kind::orderBy('id', 'ASC') ->get();
         $dr_master=Dr_master::orderBy('sort', 'ASC') ->get();
          if (Session::has('ssReport')) {
                   $url = Session::get('ssReport');
                   $currentLink = URL::full(); 
                    array_push($url, $currentLink); 
                    Session::flash('ssReport', $url);
                    }
            $upd=-1;        
        return view('dr_report.detailreport', compact('client','division','kind','dr_master','reports'
          ,'upd'))->with('message', 'Succes! Report was updated');
     
      
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
            if (Session::has('ssReport')) {
                    Session::keep('ssReport');
                    }
           $reportsQuery = Dr_report::sortable()
          ->leftjoin('users', 'dr_report.userID', '=', 'users.id')
          ->leftjoin('client', 'dr_report.companyID', '=', 'client.id')
          ->leftjoin('division', 'dr_report.divisionID', '=', 'division.id')
          ->leftjoin('dr_kind', 'dr_report.kind', '=', 'dr_kind.id')
          ->leftjoin('dr_master as way', 'dr_report.way', '=', 'way.id')
          ->where('dr_report.id','=',$id)
          ->select('dr_report.*', 'client.companyname','division.divisionname'
            ,'users.name as userName','way.name as wayname','dr_kind.name as kindname')
          ->orderBy('id', 'ASC');  
         $reports=$reportsQuery   ->first();

         $division = Division::orderBy('divisionname', 'ASC')->where("companyid","=", $reports->companyID) ->get();

         $client = Client::orderBy('orders', 'desc') ->get();
         $kind=Dr_kind::orderBy('id', 'ASC') ->get();
         $dr_master=Dr_master::orderBy('sort', 'ASC') ->get();
        return view('dr_report.editeport', compact('client','division','kind','dr_master','reports'));
      
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
            if (Session::has('ssReport')) {
                    Session::keep('ssReport');
                    }       
          $reportUDP = Dr_report::find($id);
          $reportUDP->companyID =  $request->get('client');
          $reportUDP->divisionID =  $request->get('division');
          $reportUDP->customer =  $request->get('customer');
          $reportUDP->visitor =  $request->get('visitor');
          $reportUDP->kind =  $request->get('kind');
          $reportUDP->way =  $request->get('way');
          $reportUDP->title =  $request->get('title');
          $reportUDP->to =  $request->get('toTime');
          $reportUDP->from =  $request->get('fromTime');
          $reportUDP->other =  $request->get('other');
          $reportUDP->detail =  $request->get('detail');
          $reportUDP->save();
         return $this->showudp($id);
        
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
      $report = Dr_report::find($id);
       $report->delete();
        return redirect('/reportSearch')->with('message', 'Succes! Report was deleted');
    }
    public function mySearch(Request $request)
    {
     
    }
    public function showClient($code)
    {
        $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
  }
   public function validateOT($from,$to,Request $request)
       {
       
        if ($from>$to) {
                $this->validate($request,[
                'fromTime' => 'required|email',
        ],[
         ' Wrong time input '
          
        ]);
        }
       
       }    
      
}