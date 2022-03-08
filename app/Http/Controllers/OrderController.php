<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Division;
use App\Orders;
use App\Master;
use App\User;
use App\Units;
use App\Library\CommonFunction;
use DB;
use Carbon\Carbon; 
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class OrderController extends Controller
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
 DB::connection()->enableQueryLog();
              $p_Status='status';
              $progressCode='progress';
              $p_Status1=0;
              $p_Status2=100;
              $master =  Master::orderBy('sort', 'ASC')->get();
              $progress=  $master;
              $units =  Units::orderBy('sort', 'ASC')->get();
              $unitSaFrom= $units ;
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
            ->paginate(50);
           //    $query = DB::getQueryLog();
           //    $lastQuery = end($query);
           // dd(  $lastQuery);
            $status = $master;
             $company=Client::all();
             
             $users=DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();
         Session::flash('backOUrl', URL::full());
         return view('order.indexorder', compact('order','status','company','users','progress','unitSaFrom'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        session_start(); 
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }

        $client = client::orderBy('companyname', 'ASC') ->get();
        
        $pic    =  DB::table('users')->where('status','=','1')
        ->orderBy('sort', 'ASC')->get();
        
        $master =  Master::orderBy('sort', 'ASC')->get();
        $units =  Units::orderBy('sort', 'ASC')->get();
        $progress=  $master;
        $type   =   $master;
        $japanese   =   $master;
        $english   =   $master;
        $warranty   =   $master;
         $sex   =   $master;
         $unitSaFrom= $units ;
          $unitSaTo= $units ;
           $unitFrom= $units ;
            $unitTo= $units ;
           
                $division=Division::all();
           
        return view('order.createorder', compact('client','pic','master','progress','type' ,'japanese' ,'english','unitSaFrom','unitSaTo','unitFrom','unitTo'
            ,'warranty' ,'sex' ,'user','division'));
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

        
             $introduceFee_PR= str_replace(",", "", $request->get('introduceFee'));
           $partnerFee_PR= str_replace(",", "", $request->get('partnerFee'));
            $salaryFrom_PR= str_replace(",", "", $request->get('salaryFrom'));
             $salaryTo_PR= str_replace(",", "", $request->get('salaryTo'));
           
              if( !is_numeric( $partnerFee_PR)){
               $partnerFee_PR= null;
               } 
               if( !is_numeric( $salaryTo_PR)){
               $salaryTo_PR= null;
               } 
                  if( !is_numeric( $salaryFrom_PR)){

               $salaryFrom_PR= null;
               } 
                   

        $this->putSessCrOrder( $request);
        $this->validate($request,[
         'client' => 'required' ,
         'division' => 'required' ,
         'pic' => 'required' , 
          'progress' => 'required' ,

          'type' => 'required' ,
          'title' => 'required' ,
         
          
          'recruit_num' =>  'required|integer|min:1|max:2000',
         
          'age' => 'required' ,
          'sex' => 'required' ,
          'progress' => 'required' ,
          'progress' => 'required' ,
         
        ],[
          'name.required' => ' The Client name field is required.'          
        ]);
        if($request->get('progress')=='99' ||$request->get('progress')=='100')
         {
             $this->validate($request,[
         'workingDate' => 'required|after:orderDate' 
        ],[
          'name.required' => ' The Client name field is required.'          
        ]);
         }
              if($request->get('progress') > 2)
         {
             $this->validate($request,[
         'introduceFee' => 'required' ,
         'salaryFrom' => 'required_without:salaryTo',
         'salaryTo' => 'required_without:salaryFrom',
         
         'orderDate' => 'required' ,
         'introducePriority' => 'required|after:orderDate' ,
         'workingDate'   => 'required|after:introducePriority' ,
        ],[
          'name.required' => ' The Client name field is required.'          
        ]);
         }
  
         
        $id_div=$request->get('division');
        $division = division::find($id_div);
        $ordernum = DB::table('order')
              ->where("divisionID","=",$id_div)
              ->orderBy('code', 'desc')->first();
              $maxnum= 0;
             if ( is_null($ordernum)) {
                $maxnum=    intval($division->code)*1000;
             }
             else { $maxnum= intval($ordernum->code);}
             
         $maxcode=$maxnum+10000000001;
         $orderCode=substr($maxcode, -10);
    
          if($request->get('progress') <= 2 && $request->get('introduceFee') == '')
              { 
                $introduceFee_PR    =   null; }
            else
            { $introduceFee_PR     =    str_replace(",", "", $request->get('introduceFee'));}


              
            $priority=0;
            if( !is_null($request->get('priority')))
            {$priority=$request->get('priority');}

            
           $invoiceck="0";
           $invoiceDate=null;
            if( !is_null($request->get('invoiceCK')))
              {$invoiceck="1"; $invoiceDate=$request->get('invoiceDate');}
            if ( !is_null($salaryTo_PR) && $salaryFrom_PR > $salaryTo_PR)  {
               $this->validate($request,[
               'salaryFrom' => 'integer|max:0' 
              ],[
                ' salary From must be smaller than To '          
              ]);

             }

        $newOrder = new orders([
        'code'=> $orderCode,
        'divisionID'=> $request->get('division'),
        'clientID'=> $request->get('client'),
        'title'=> $request->get('title'),
        'type'=> $request->get('type'),
        'orderDate'=> $request->get('orderDate'),
        'progress'=> $request->get('progress'),
        'workingDate'=> $request->get('workingDate'),
        'introduceDate'=> $request->get('introducePriority'),
        'workingPlace'=> $request->get('workingPlace'),
        'pic_s'=> $request->get('pic'),
        'recruit_num'=> $request->get('recruit_num'),
        'age'=> $request->get('age'),
        'sex'=> $request->get('sex'),
        'JLevel'=> $request->get('japanese'),
        'ELevel'=> $request->get('english'),
        'skill'=> $request->get('skill'),
        'introduceFee'=> $introduceFee_PR,  
        
         'partner'=> $request->get('partner'),
         'partnerFee'=> $partnerFee_PR,  
        'warrantyPeriod'=> $request->get('warrantlyPeriod'),
        'warranty'=> $request->get('warranty'),
        'note'=> $request->get('note'),
        'indispensable'=> $request->get('indispensable'),
        
        'salaryFrom'=> $salaryFrom_PR,
        'salaryTo'=>$salaryTo_PR,
        'unitSaFrom'=>  $request->get('unitSaFrom'),
        'unitSaTo'=>  $request->get('unitSaTo'),
        'unitFrom'=> $request->get('unitFrom'),
         'invoiceCK' => $invoiceck ,
            'invoiceDate' =>$invoiceDate,
            'priority'=> $priority,
            // 'priority'=> $request->get('priority'),
        'user' =>$user->userName
          
        ]);
            $newOrder->save();
             $this->clearSessCrOrder();
             $order = DB::table('order')
              ->where("order.code","=", $orderCode)
             ->first();
           return $this->show($order->id);
             
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

         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
        $progressCode='progress';
         $order = DB::table('order')
            ->leftjoin('division', 'order.divisionID', '=', 'division.id')
            ->leftjoin('client', 'division.companyid', '=', 'client.id')
            ->leftjoin('users', 'order.pic_s', '=', 'users.id')
           ->leftJoin('master as pro', function ( $join ) use  ($progressCode) {
            $join->on('order.progress', '=', 'pro.code')
            ->where('pro.type','=',$progressCode);})
            ->leftjoin('master as type', 'order.type', '=', 'type.id')
            ->leftjoin('master as jp', 'order.JLevel', '=', 'jp.id')
            ->leftjoin('master as eng', 'order.ELevel', '=', 'eng.id')
            ->leftjoin('master as waranty', 'order.warranty', '=', 'waranty.id')
            ->leftjoin('master as sex', 'order.sex', '=', 'sex.id')
            ->leftjoin('units as unitSaFrom', 'order.unitSaFrom', '=', 'unitSaFrom.id')
            ->leftjoin('units as unitSaTo', 'order.unitSaTo', '=', 'unitSaTo.id')
            ->leftjoin('units as unitFrom', 'order.unitFrom', '=', 'unitFrom.id')
            ->leftjoin('units as unitTo', 'order.unitTo', '=', 'unitTo.id')
            ->select('order.*',  'division.divisionname as divisionname','client.companyname as clientName','users.name as picName','pro.name as progressName','type.name as typeName','jp.name as jpName','eng.name as engName','waranty.name as warrantyName','sex.name as sexName','unitSaFrom.code as unitSaFrom','unitSaTo.code as unitSaTo'
              ,'unitFrom.code as unitFrom','unitTo.code as unitTo')
            ->orderBy('id', 'desc')
            ->where("order.id","=", $id)
            ->first();
             $candilist =DB::table('ca_OrderCandidates as oc')
        ->join('candidates as ca','ca.id','=','oc.idCandidates')  
        ->leftjoin('master as statustbl','oc.status','=','statustbl.id')  
        ->select('ca.*','oc.id as ocID','oc.*','oc.idOrder as ocOrderID','ca.id as caid','statustbl.name as statusName' )
        ->where('idOrder','=',$id)
        ->orderBy('oc.id', 'DESC')->get();  
            // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    } else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }if (Session::has('backOUrl')) {

            Session::keep('backOUrl');
            }

          // end session
            return view('order.detailorder', compact('order','id','candilist'));
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
         $progressCode='progress';
         $order = DB::table('order')
            ->leftjoin('division', 'order.divisionID', '=', 'division.id')
            ->leftjoin('client', 'division.companyid', '=', 'client.id')
            ->leftjoin('users', 'order.pic_s', '=', 'users.id')
            ->leftJoin('master as pro', function ( $join ) use  ($progressCode) {
            $join->on('order.progress', '=', 'pro.code')
                 ->where('pro.type','=',$progressCode);})
             ->leftjoin('master as sex', 'order.sex', '=', 'sex.id')
            ->leftjoin('master as type', 'order.type', '=', 'type.id')
            ->leftjoin('master as jp', 'order.JLevel', '=', 'jp.id')
            ->leftjoin('master as eng', 'order.ELevel', '=', 'eng.id')
            ->leftjoin('master as warranty', 'order.warranty', '=', 'warranty.id')
            ->select('order.*',  'division.divisionname as divisionName','client.companyName as clientName','users.name as picName','pro.name as progressName','type.name as typeName','jp.name as jpName','eng.name as engName','warranty.name as warrantyName','sex.name as sexName'
          )
            ->orderBy('id', 'desc')
            ->where("order.id","=", $id)
            ->first();
         //
           $client = client::orderBy('companyname', 'ASC') ->get();
           $division=DB::table('division')
            ->where('companyid','=',$order->clientID)
            ->get();

        $pic    =  DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();
        $master =  Master::orderBy('sort', 'ASC')->get();
        $progress=  $master;
        $type   =   $master;
        $japanese   =   $master;
        $english   =   $master;
        $warranty   =   $master;     
        $sex   =   $master;    
         $units =  Units::orderBy('sort', 'ASC')->get();
         $unitSaFrom= $units ;
         $unitSaTo= $units ;
         $unitFrom= $units ;
         $unitTo= $units ;
         $actionID='0';
          // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }else if (Session::has('backOUrl')) {

                      Session::keep('backOUrl');
                    }

         // end session
                    
            return view('order.editorder', compact('order','division','id','client','pic','master','progress','type' ,'japanese' ,'english','warranty','sex'
          ,'unitSaFrom','unitSaTo','unitFrom','unitTo','actionID'));
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
            // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }else if (Session::has('backOUrl')) {

                      Session::keep('backOUrl');
                    }

         // end session


         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
            $introduceFeeMax_PR= str_replace(",", "", $request->get('introduceFeeMax'));
            $introduceFee_PR= str_replace(",", "", $request->get('introduceFee'));
            $partnerFee_PR= str_replace(",", "", $request->get('partnerFee'));
            $salaryFrom_PR= str_replace(",", "", $request->get('salaryFrom'));
            $salaryTo_PR= str_replace(",", "", $request->get('salaryTo'));
            


         $this->validate($request,[
         'client' => 'required' ,
         'division' => 'required' ,
         'pic' => 'required' , 
          'progress' => 'required' ,
          'type' => 'required' ,
          'title' => 'required' ,
          'orderDate' => 'required' ,
          'introducePriority' => 'required|after:orderDate' ,
          'recruit_num' => 'required|integer|min:1|max:2000',
          
          'age' => 'required' ,
          'sex' => 'required' ,
          'progress' => 'required' ,
          'progress' => 'required' ,
        
        ],[
          'name.required' => ' The Client name field is required.'          
        ]);
         if($request->get('progress')=='99' ||$request->get('progress')=='100')
         {
             $this->validate($request,[
         'workingDate' => 'required|after:orderDate' 
        ],[
          'name.required' => ' The Client name field is required.'          
        ]);
         }
         if($request->get('progress') > 2)
         {
             $this->validate($request,[
         'introduceFee' => 'required' ,'salaryFrom' => 'required' ,
          'salaryFrom' => 'required_without:salaryTo',
          'salaryTo' => 'required_without:salaryFrom',
          

        ],[
          'name.required' => ' The Client name field is required.'          
        ]);
         }
       
          if($request->get('introduceFeeMax') == ''|| $request->get('introduceFeeMax') == null)
         {$introduceFeeMax_PR=null;}
       elseif ($introduceFeeMax_PR<$introduceFee_PR)  {
         $this->validate($request,[
         'introduceFeeMax' => 'integer|max:0' 
        ],[
          ' Introduce Fee From must be smaller than To '          
        ]);

       }  
         if( !is_numeric( $partnerFee_PR)){
               $partnerFee_PR= null;
               } 
               if( !is_numeric( $salaryTo_PR) || $salaryTo_PR==0 ){
               $salaryTo_PR= null;
               } 
                  if( !is_numeric( $salaryFrom_PR)){
                    
               $salaryFrom_PR= null;
               }
               
               $priority=0;
               if( !is_null($request->get('priority')))
               {$priority=$request->get('priority');}

            $invoiceck="0";
            $invoiceDate=null;
            if( !is_null($request->get('invoiceCK')))
              {$invoiceck="1";$invoiceDate=$request->get('invoiceDate');}
              if ( !is_null($salaryTo_PR) && $salaryFrom_PR > $salaryTo_PR)  {
               $this->validate($request,[
               'salaryFrom' => 'integer|max:0' 
              ],[
                ' salary From must be smaller than To '          
              ]);

             }
           


            $orderS =  Orders::find($id);
            $orderS->divisionID        =    $request->get('division');
            $orderS->clientID          =    $request->get('client');
            $orderS->title             =    $request->get('title');
            $orderS->type              =    $request->get('type');
            $orderS->orderDate         =    $request->get('orderDate');
            $orderS->progress          =    $request->get('progress');
            $orderS->workingDate       =    $request->get('workingDate');
            $orderS->introduceDate     =    $request->get('introducePriority');
            $orderS->workingPlace      =    $request->get('workingPlace');
            $orderS->pic_s             =    $request->get('pic');
            $orderS->recruit_num       =    $request->get('recruit_num');
            $orderS->age               =    $request->get('age');
            $orderS->sex               =    $request->get('sex');
            $orderS->JLevel            =    $request->get('japanese');
            $orderS->ELevel            =    $request->get('english');
            $orderS->skill             =    $request->get('skill');
            if($request->get('progress') <= 2 && $request->get('introduceFee') == '')
              { 
                $orderS->introduceFee      =   null;
                $orderS->salaryFrom      =   null;
                 }
            else
            {$orderS->introduceFee      =    str_replace(",", "", $request->get('introduceFee'));
            $orderS->introduceFeeMax      =   $introduceFeeMax_PR;}
            $orderS->partner    =    $request->get('partner');
            if( is_numeric( $partnerFee_PR)){
                 $orderS->partnerfee    =   $partnerFee_PR;
               } else {$orderS->partnerfee    = null;}
             if( is_numeric( $salaryFrom_PR)){
                 $orderS->salaryFrom    =   $salaryFrom_PR;
               } else {$orderS->salaryFrom    = null;}   
             if( is_numeric( $salaryTo_PR)){
                 $orderS->salaryTo    =   $salaryTo_PR;
               } else {$orderS->salaryTo    = null;} 
    
            $orderS->warrantyPeriod    =    $request->get('warrantlyPeriod');
            $orderS->warranty          =    $request->get('warranty');
            $orderS->note              =    $request->get('note');
            $orderS->indispensable     =    $request->get('indispensable');
          
            $orderS->unitSaFrom        =    $request->get('unitSaFrom');  
            $orderS->unitSaTo        =    $request->get('unitSaTo');
            $orderS->unitFrom        =    $request->get('unitFrom');  
            $orderS->unitTo        =    $request->get('unitTo');
            $orderS->priority     = $priority;
            $orderS->invoiceCK        =   $invoiceck;  
            $orderS->invoiceDate        =    $invoiceDate;   
            $orderS->upd               =    $user->userName;  
              
              if(!is_null($request->actionID))
             {
				 $id_div=$request->get('division');
				   $division = division::find($id_div);
              $ordernum = DB::table('order')
              ->where("divisionID","=",$orderS->divisionID)
              ->orderBy('code', 'desc')->first();
              $maxnum= 0;
			     $maxnum= 0;
             if ( is_null($ordernum)) {
                $maxnum=    intval($division->code)*1000;
             }
             else { $maxnum= intval($ordernum->code);}
              $maxcode=intval($maxnum)+10000000001;
              $orderCode=substr($maxcode, -10);
              $cloneOrder = $orderS->replicate();
              $newID = CommonFunction::getAutoInscrement("order");
              $cloneOrder->id=$newID ;
              $cloneOrder->code=$orderCode ;
              $cloneOrder->save();
            


                        // create sesion
            if (Session::has('ssClient')) {

                    Session::keep('ssClient');
                              return ($url = Session::get('ssClient')) 
   ? Redirect::to($url[2])->with('message', 'Succes! Order was cloned') // Will redirect 2 links back 
   : 
            $this->show($id);
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                                return ($url = Session::get('ssDivision')) 
   ? Redirect::to($url[1])->with('message', 'Succes! Order was cloned') // Will redirect 2 links back 
   : 
             $this->show($id);
                    } else  if (Session::has('backOUrl')) {

                      return ($url = Session::get('backOUrl')) 
   ? Redirect::to($url)->with('message', 'Succes! Order was cloned') // Will redirect 2 links back 
   : 
             $this->show($id);
                    }

                    return $this->show($newID);
                
  // end session
            

             } else
             {

            $orderS->save();
            $this->show($id);
            }
              // create sesion
              if (Session::has('ssClient')) {
  //                   Session::keep('ssClient');
  //                             return ($url = Session::get('ssClient')) 
  //  ? Redirect::to($url[2])->with('message', 'Succes! Order was updated') // Will redirect 2 links back 
  //  : 
            $this->show($id);
                    }else if (Session::has('ssDivision')) {
  //                     Session::keep('ssDivision');
  //                               return ($url = Session::get('ssDivision')) 
  //  ? Redirect::to($url[1])->with('message', 'Succes! Order was updated') // Will redirect 2 links back 
  //  : 
             $this->show($id);
                    } else  if (Session::has('backOUrl')) {

  //                     return ($url = Session::get('backOUrl')) 
  //  ? Redirect::to($url)->with('message', 'Succes! Order was updated') // Will redirect 2 links back 
  //  : 
             $this->show($id);
                    }
                    return $this->show($id);
  // end session
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
        $order = Orders::find($id);
        $order->delete();
         // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                              return ($url = Session::get('ssClient')) 
   ? Redirect::to($url[2])->with('message', 'Succes! Order was deleted') // Will redirect 2 links back 
   : 
             redirect('/order');
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                                return ($url = Session::get('ssDivision')) 
   ? Redirect::to($url[1])->with('message', 'Succes! Order was deleted') // Will redirect 2 links back 
   : 
              redirect('/order');
                    }
                
  // end session
  
    }
    public function canSearch(Request $request){
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
          $request->flash();
          $orderID=$request->input('orderID');
          $results = array();
          $conditions = DB::table('condition')       
          ->where("condition.order_id","=", $orderID)
          ->where("condition.status","=",'〇')
          ->get();
          $countCondition=count($conditions);
           $candidates = DB::table('candiate')
            ->get();   
    return view('search.autocomplete', compact('results'));
}

    public function orderSearch(Request $request)
    {
      $request->flash();
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
           DB::connection()->enableQueryLog();
         $master =  Master::orderBy('sort', 'ASC')->get();
         $progress=  $master;
         $statusid=$request->input('status');
         $companyname=$request->input('order_companysrc');
         $pics=$request->input('pics'); 
         $progressSC=$request->input('progress'); 
         $invoiceCK=$request->input('invoiceCK'); 
         $orderFrom=$request->input('orderFrom'); 
         $orderTo=$request->input('orderTo'); 
         $introduceFrom=$request->input('introduceFrom'); 
         $introduceTo=$request->input('introduceTo'); 
         $workingFrom=$request->input('workingFrom'); 
         $workingTo=$request->input('workingTo'); 
         $progressCode='progress';
          $arrayStatus=array(99,50);
          $p_Status1=0;
         $p_Status2=200;
          $units =  Units::orderBy('sort', 'ASC')->get();
          $unitSaFrom= $units ;
         if ($statusid==1) {
          
          $arrayStatus=array(10,20,30,40);
         } 
        
         elseif ($statusid==3) {
               $arrayStatus=array(31,110);
         }
            elseif ($statusid==6) {
                $arrayStatus=array(50,99);
         
         }
            $salaryFrom_PR= str_replace(",", "", $request->get('salaryFrom'));
            $salaryTo_PR= str_replace(",", "", $request->get('salaryTo'));
             $unitSaFromS= str_replace(",", "", $request->get('unitSaFrom'));
               if( !is_numeric( $salaryTo_PR)){
               $salaryTo_PR= null;
               } 
               if( !is_numeric( $salaryFrom_PR)){
               $salaryFrom_PR= null;
               } 
                $query = DB::getQueryLog();
            $order = Orders::sortable()
            ->leftjoin('division', 'order.divisionID', '=', 'division.id')
            ->leftjoin('client', 'order.clientID', '=', 'client.id')
            ->leftjoin('users', 'order.pic_s', '=', 'users.id')
            ->leftjoin('master as status', 'order.status', '=', 'status.id')
            ->leftjoin('master as pro', function ( $join ) use  ($progressCode) {
                    $join->on('order.progress', '=', 'pro.code')
                         ->where('pro.type','=',$progressCode);})
            ->leftjoin('master as type', 'order.type', '=', 'type.id')
            ->leftjoin('master as jp', 'order.JLevel', '=', 'jp.id')
            ->leftjoin('master as eng', 'order.ELevel', '=', 'eng.id')
            ->leftjoin('master as waranty', 'order.warranty', '=', 'waranty.id')
            ->leftjoin('units as units', 'order.unitFrom', '=', 'units.id')
            ->select('order.*', 'division.divisionname as divisionName','client.companyname as companyname','users.name as pics','status.name as statusName','pro.name as progressName','type.name as typeName','jp.name as jpName','eng.name as engName','waranty.name as warantyName','pro.name as progressName','units.code as unitName' 
              )
            ->where(function ($query)use ($companyname) {
                 $query->whereNull('client.companyname')
                    ->orWhere ('client.companyname','like','%' . $companyname . '%' );
                    })
            ->where(function ($query) use ( $arrayStatus,$statusid) {
             if (!is_null($statusid)) {
                 $query-> whereIn('order.progress', $arrayStatus);
             }

                        
                    })              
            ->where(function ($query)use ($pics) {
                 $query->whereNull('order.pic_s')
                       ->orWhere ('order.pic_s','like','%' .$pics. '%');
                    })
            ->where(function ($query)use ($progressSC) {
                 $query->whereNull('pro.name')
                       ->orWhere ('pro.name','like', '%' . $progressSC. '%'  );
                    })
                        ->where(function ($query)use ($invoiceCK) {
                 $query->whereNull('order.invoiceCK')
                       ->orWhere ('order.invoiceCK','like','%' . $invoiceCK . '%' );
                    })
            ->where(function ($query)use ($orderFrom,$orderTo) {
              if (!is_null($orderFrom) && !is_null($orderTo)) {
                 $query->whereBetween('orderDate',[$orderFrom,$orderTo]);
              } elseif (!is_null($orderFrom) ) {
               $query->where('orderDate','>=',$orderFrom);
              }
               elseif (!is_null($orderTo) ) {
               $query->where('orderDate','<=',$orderTo);
              }
            })     
              ->where(function ($query)use ($introduceFrom,$introduceTo) {
              if (!is_null($introduceFrom) && !is_null($introduceTo)) {
                 $query->whereBetween('introduceDate',[$introduceFrom,$introduceTo]);
              } elseif (!is_null($introduceFrom) ) {
               $query->where('introduceDate','>=',$introduceFrom);
              }
               elseif (!is_null($introduceTo) ) {
               $query->where('introduceDate','<=',$introduceTo);
              }
            })   
               ->where(function ($query)use ($workingFrom,$workingTo) {
              if (!is_null($workingFrom) && !is_null($workingTo)) {
                 $query->whereBetween('workingDate',[$workingFrom,$workingTo]);
              } elseif (!is_null($workingFrom) ) {
               $query->where('workingDate','>=',$workingFrom);
              }
               elseif (!is_null($workingTo) ) {
               $query->where('workingDate','<=',$workingTo);
              }
            })    
            ->where(function ($query)use ($salaryFrom_PR) {
             if (!is_null($salaryFrom_PR) ) {
              $query->where('salaryFrom','>=',$salaryFrom_PR);
              $query->orwhere('salaryTo','>=',$salaryFrom_PR);
             }
                      }) 
            ->where(function ($query)use ($salaryTo_PR) {
             if (!is_null($salaryTo_PR) ) {
              $query->where('salaryFrom','<=',$salaryTo_PR);
              $query->where('salaryTo','<=',$salaryTo_PR);
             }
            })   
             ->where(function ($query)use ($salaryFrom_PR,$salaryTo_PR,$unitSaFromS) {   
                if (!is_null($salaryTo_PR )|| !is_null($salaryFrom_PR) ) {
                    $query->where('unitSaFrom','=',$unitSaFromS);
              $query->orwhere('unitSaTo','=',$unitSaFromS);
            }
             })          
            ->orderBy('id', 'desc')
             ->sortable()
            ->paginate(50);

    // $query = DB::getQueryLog();
    //           $lastQuery = end($query);
    //        dd(  $lastQuery);
             $status =  $master;
             $company=Client::all();
               $users=DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();
           
             Session::flash('backOUrl', URL::full());

         return view('order.indexorder', compact('order','status','company','users','progress','unitSaFrom'));
    }
    public function clearSessCrOrder()
    {
        session()->put('forms.ssClientID','');
        session()->put('forms.ssPicID','');
        session()->put('forms.ssProgressID','');
        session()->put('forms.ssTypeID','');
        session()->put('forms.ssSexID','');
        session()->put('forms.ssDivisionID','');
        session()->put('forms.ssJapaneseID','');
        session()->put('forms.ssEnglishID','');
        session()->put('forms.ssWarrantyID','');      
    }
    public function putSessCrOrder(Request $request)
    {
        session()->put('forms.ssClientID', $request->get('client'));
        session()->put('forms.ssDivisionID', $request->get('division'));
        session()->put('forms.ssPicID', $request->get('pic'));
        session()->put('forms.ssProgressID', $request->get('progress'));
        session()->put('forms.ssTypeID', $request->get('type'));
        session()->put('forms.ssSexID', $request->get('sex'));
        session()->put('forms.ssJapaneseID', $request->get('japanese'));
        session()->put('forms.ssEnglishID', $request->get('english'));
        session()->put('forms.ssWarrantyID', $request->get('warranty'));
    }
        public function clone($id)
    {
        $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         $progressCode='progress';
         $order = DB::table('order')
            ->leftjoin('division', 'order.divisionID', '=', 'division.id')
            ->leftjoin('client', 'division.companyid', '=', 'client.id')
            ->leftjoin('users', 'order.pic_s', '=', 'users.id')
            ->leftJoin('master as pro', function ( $join ) use  ($progressCode) {
            $join->on('order.progress', '=', 'pro.code')
                 ->where('pro.type','=',$progressCode);})
             ->leftjoin('master as sex', 'order.sex', '=', 'sex.id')
            ->leftjoin('master as type', 'order.type', '=', 'type.id')
            ->leftjoin('master as jp', 'order.JLevel', '=', 'jp.id')
            ->leftjoin('master as eng', 'order.ELevel', '=', 'eng.id')
            ->leftjoin('master as warranty', 'order.warranty', '=', 'warranty.id')
            ->select('order.*',  'division.divisionname as divisionName','client.companyName as clientName','users.name as picName','pro.name as progressName','type.name as typeName','jp.name as jpName','eng.name as engName','warranty.name as warrantyName','sex.name as sexName'
          )
            ->orderBy('id', 'desc')
            ->where("order.id","=", $id)
            ->first();

         //
           $client =  Client::all();
           $division=DB::table('division')
            ->where('companyid','=',$order->clientID)
            ->get();

        $pic    =  DB::table('users')->where('status','=','1')->get();
        $master =  Master::orderBy('sort', 'ASC')->get();
        $progress=  $master;
        $type   =   $master;
        $japanese   =   $master;
        $english   =   $master;
        $warranty   =   $master;     
        $sex   =   $master;    
         $units =  Units::orderBy('sort', 'ASC')->get();
         $unitSaFrom= $units ;
         $unitSaTo= $units ;
         $unitFrom= $units ;
         $unitTo= $units ;
         $actionID='1';
            // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }else if (Session::has('backOUrl')) {

                      Session::keep('backOUrl');
                    }

         // end session
            return view('order.cloneorder', compact('order','division','id','client','pic','master','progress','type' ,'japanese' ,'english','warranty','sex'
          ,'unitSaFrom','unitSaTo','unitFrom','unitTo','actionID'));
    }


     
}
