<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Division;
use App\Staff;
use App\District;
use App\Province;
use App\Nation;
use App\Master;
use App\User;
use App\Orders;
use DB;
use Session;
use Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
class DivisionController extends Controller
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
   $imp="Imp";
       $p_Introduce="Introduce";
       $p_advertise='Ad';
       $status='clientStatus';

        $this->clearSessDiv();


       $division = Division::sortable()
           ->leftjoin('users', 'division.pic_s', '=', 'users.id')
           ->leftjoin('nation', 'division.national_ID', '=', 'nation.Id')
           ->leftjoin('client', 'client.id', '=', 'division.companyid')
           ->leftjoin('province', 'division.provinceId', '=', 'province.Id')
           ->leftjoin('district', 'division.districtId', '=', 'district.id')
            ->leftJoin('master as imp', function ( $join ) use  ($imp) {
            $join->on('division.rate','=','imp.code')
                 ->where('imp.type','=',$imp);
             })
             ->leftJoin('master as status', function ( $join ) use  ($status) {
            $join->on('division.status','=','status.val')
                 ->where('status.type','=',$status);
             })
            

            ->leftJoin('master as introduce', function ( $join ) use  ($p_Introduce) {
            $join->on('division.introduce','=','introduce.code')
                 ->where('introduce.type','=',$p_Introduce);
             })
              ->leftJoin('master as advertise', function ( $join ) use  ($p_advertise) {
            $join->on('division.advertise','=','advertise.code')
                 ->where('advertise.type','=',$p_advertise);
             })
           ->whereNull('division.status')
          ->select('division.*', 'province.Name as provinceName','district.Name as districtName','nation.commonName as nationName','client.companyname as clientName','users.name as pics','client.code as clientCode','imp.name as rate2','introduce.name as introduceName','advertise.name as advertiseName','status.name as statusName')
            ->orderBy('clientName', 'ASC')
          ->sortable()
           ->paginate(50);
            $national = Nation::all();
             $master=DB::table('master')
            ->get();
             $introduce=$master;
              $advertise=$master;
              $province = Province::all();
               $province=$province->sortBy('SortOrder');

               $districtDB=DB::table('district')
          ->where('ProvinceId','=',$province)
          ->get();
          $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssDivision', $links);
         $users = DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();
            return view('division.searchDivision',  compact('division','pic','province','national','district','master','introduce','advertise','users','districtDB'));
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
         $province = Province::all();
           $province=$province->sortBy('SortOrder');
         $national = Nation::all();
         $id=0;
          $client = client::orderBy('companyname', 'ASC') ->get();
         $master=DB::table('master')->get();
         $introduce=$master;
         $advertise=$master;
         $status= $master;
         $users = DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();
             $district = DB::table('district')
            ->where("ProvinceId","=", 1)
            ->orderBy('Name', 'ASC')
            ->get();
         return view('division.createdivision2',compact('id','province','national','master','introduce','advertise','users','client','district','user','status'));
       
    }
       public function createNew()
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
        return view('division.editdivision', compact('id'));
       
    }
        public function addnew(Request $request)
    {
        $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         // $this->clearSessInpDiv($request);  
         $province = Province::orderBy('Name', 'ASC') ->get();
         $national = Nation::all();
         $id=$request->get('companyid2');
          $client = client::find($id);
         $master=DB::table('master')->get();
         $introduce=$master;
         $advertise=$master;
         $status=$master;
         $users = DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();
             $district = DB::table('district')
            ->where("ProvinceId","=", 1)
			->orderBy('Name', 'ASC') ->get();
               // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    } else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }


          // end session
         return view('division.createdivision',compact('id','province','national','master','introduce','advertise','users','client','district','status'));

       
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
              $this->putSessInpDiv($request); 
               // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }

         // end session
          
          if ($request->get('national')== 1) {
                        $this->validate($request,['divisionname' => 'required',
                                      'mobile' => 'required',
                                      'pic_s' => 'required',
                                      'province' => 'required',
                                      'district' => 'required',
                                      'address' => 'required'
        ],[ 'name.required' => ' The first name field is required.'
        ]);
 
                       }   
                       else {
                         $this->validate($request,['divisionname' => 'required',
                                      'mobile' => 'required',
                                      'pic_s' => 'required'
        ],[ 'name.required' => ' The first name field is required.'
        ]);
                             }

                                             
          $id_comp=$request->get('ivbId');

          if(  $id_comp==0) {

               $id_comp=$request->get('clientCreate');


               } 
               
          $clientnum = DB::table('division')
              ->where("companyid","=",$id_comp)
             ->orderBy('id', 'desc')->first();
              $maxnum= 0;
             if ( is_null($clientnum)) {
                $maxnum= $id_comp*100;
             }
             else { $maxnum= intval($clientnum->code);}

       
         $maxcode=$maxnum+10000001;
         $divisionCode=substr($maxcode, -7);
          $pic_s=DB::table('users')->orderBy('sort', 'ASC')
         ->get();
         $province = Province::orderBy('Name', 'ASC') ->get();
          $division2 = new division([
         'companyid'=> $id_comp,
         'code' => $divisionCode,
         'divisionname' => $request->get('divisionname'),
         'mobile'=> $request-> get('mobile'),
         'email'=> $request->get('email'),
         'pic_s'=> $request->get('pic_s'),
         'address'=> $request->get('address'),
         'introduce'=> $request->get('introduce'),
         'advertise'=> $request->get('advertise'),
         'rate'=> $request->get('rate'),
          'condition'=> $request->get('condition'),
         'provinceId'=> $request->get('province'),
         'districtId'=> $request->get('district'),
         'national_ID'=> $request->get('national'),
         'worktime1'=> $request->get('worktime1'),
          'worktime2'=> $request->get('worktime2'),
          'holidays'=> $request->get('holidays'),
          'review'=> $request->get('review'),
          'yearBonus'=> $request->get('yearBonus'),
          'welfare'=> $request->get('welfare'),
           'status'=> $request->get('status'),
          'otherWelfare'=> $request->get('otherWelfare'),
            ]);
        $division2->save();
        $division = DB::table('division')
        ->where("code","=", $divisionCode)
        ->first();
        $id= $division->id;

            $this->clearSessInpDiv($request);  
            // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                              return ($url = Session::get('ssClient')) 
   ? Redirect::to($url[1])->with('message', 'Succes! Division was created') // Will redirect 2 links back 
   : 
             $this->show($id);
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                                return ($url = Session::get('ssDivision')) 
   ? Redirect::to($url[0])->with('message', 'Succes! Pic was created') // Will redirect 2 links back 
   : 
             $this->show($id);
                    }
                
  // end session
      return $this->show($id);
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
          $imp="Imp";
          $status='clientStatus';
       $p_Introduce="Introduce";
       $p_advertise='Ad';
       $division = DB::table('division')
           ->leftjoin('users', 'division.pic_s', '=', 'users.id')
           ->leftjoin('nation', 'division.national_ID', '=', 'nation.Id')
           ->leftjoin('client', 'client.id', '=', 'division.companyid')
           ->leftjoin('province', 'division.provinceId', '=', 'province.Id')
           ->leftjoin('district', 'division.districtId', '=', 'district.id')
            ->leftJoin('master as imp', function ( $join ) use  ($imp) {
            $join->on('division.rate','=','imp.code')
                 ->where('imp.type','=',$imp);
             })
             ->leftJoin('master as status', function ( $join ) use  ($status) {
            $join->on('division.status','=','status.val')
                 ->where('status.type','=',$status);
             })
            ->leftJoin('master as introduce', function ( $join ) use  ($p_Introduce) {
            $join->on('division.introduce','=','introduce.code')
                 ->where('introduce.type','=',$p_Introduce);
             })
              ->leftJoin('master as advertise', function ( $join ) use  ($p_advertise) {
            $join->on('division.advertise','=','advertise.code')
                 ->where('advertise.type','=',$p_advertise);
             })
             ->where("division.id","=", $id)
          ->select('division.*', 'province.Name as provinceName','district.Name as districtName','nation.commonName as nationName','client.companyname as clientName','users.name as name_s','client.code as clientCode','imp.name as rate2','introduce.name as introduceName','advertise.name as advertiseName','status.name as statusName')
          ->first();
           $staff = DB::table('staff')
          ->where("staff.division_id","=", $id)
          ->orderBy('lastName', 'ASC')
          ->orderBy('firstName', 'ASC')
          ->paginate(50);
            $p_Status1=0;
              $p_Status2=120;
               $progressCode='progress';
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
            ->select('order.*', 'division.divisionname as divisionName','client.companyname as clientName','users.name as pics','status.name as statusName','pro.name as progressName','type.name as typeName','jp.name as jpName','eng.name as engName','waranty.name as warantyName')
            ->where("divisionID","=", $id)
              ->where(function ($query) use ($p_Status1,$p_Status2) {
                 $query->Where ('order.progress','>=', $p_Status1  )
                       ->Where ('order.progress','<', $p_Status2);
                      
                    })
            ->orderBy('id', 'desc')
             ->sortable()
            ->paginate(50);
            $delCount=$staff->count()+$order->count();
            // create session
            if (Session::has('ssClient')) {
                   $urlOld = Session::get('ssClient');
                   $url=array();
                   array_push($url, $urlOld[0]); 
                   array_push($url, $urlOld[1]);
                   $currentLink = URL::full(); 
                    array_push($url, $currentLink); 
                    Session::flash('ssClient', $url);           
               }  if (Session::has('ssDivision')) {
                 $links =  Session::get('ssDivision');
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssDivision', $links);
               }
            // end create session

            return view('division.detaildivision', compact('division','id','staff','order','delCount'));
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
       $imp="Imp";
       $status="clientStatus";
       $p_Introduce="Introduce";
       $p_advertise='Ad';
          $division = DB::table('division')
          ->leftjoin('client', 'client.id', '=', 'division.companyid')
           ->leftjoin('users', 'division.pic_s', '=', 'users.id')
           ->leftjoin('nation', 'division.national_ID', '=', 'nation.Id')
           ->leftjoin('province', 'division.provinceId', '=', 'province.Id')
           ->leftjoin('district', 'division.districtId', '=', 'district.id')
           ->leftJoin('master as imp', function ( $join ) use  ($imp) {
            $join->on('division.rate','=','imp.code')
                 ->where('imp.type','=',$imp);
             })
         
            ->leftJoin('master as introduce', function ( $join ) use  ($p_Introduce) {
            $join->on('division.introduce','=','introduce.code')
                 ->where('introduce.type','=',$p_Introduce);
             })
              ->leftJoin('master as advertise', function ( $join ) use  ($p_advertise) {
            $join->on('division.advertise','=','advertise.code')
                 ->where('advertise.type','=',$p_advertise);
             })
            ->where("division.id","=", $id)
          ->select('division.*', 'users.name as name_s','province.Name as provinceName','district.Name as districtName','nation.commonName as nationName','imp.name as rate2','introduce.name as introduceName','advertise.name as advertiseName','client.companyname as clientName','client.code as clientCode')
          ->first();
         $province = Province::orderBy('Name', 'ASC') ->get();
         $users = DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();
         $pic=DB::table('staff')
         ->where("division_id","=", $id)
           ->paginate(50);
            $national = Nation::all();
            $district = DB::table('district')
            ->where("ProvinceId","=", $division->provinceId)->get();
             $master=DB::table('master')
            ->get();
             $introduce=$master;
              $advertise=$master;
              $status=$master;
               // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    } else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }

          // end session
              return view('division.editdivision', compact('division','id','pic','province','national','district','master','introduce','advertise','users','status'));
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
           // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }

         // end session
               if ($request->get('national')== 1) {
                        $this->validate($request,['divisionname' => 'required',
                                      'mobile' => 'required',
                                      'pic_s' => 'required',
                                      'province' => 'required',
                                      'district' => 'required',
                                      'address' => 'required'
        ],[ 'name.required' => ' The first name field is required.'
        ]);
 
                       }   
                       else {
                         $this->validate($request,['divisionname' => 'required',
                                      'mobile' => 'required',
                                      'pic_s' => 'required'
        ],[ 'name.required' => ' The first name field is required.'
        ]);
                             } 
        $pic_s=DB::table('users')->orderBy('sort', 'ASC')
        ->get();
         $province = Province::all();
           $division2 = division::find($id);
           $division2->divisionname =  $request->get('divisionname');
           $division2->mobile = $request-> get('mobile');
           $division2->email =  $request->get('email');
            $division2->pic_s =  $request->get('pic_s');
            $division2->email =  $request->get('email');
            $division2->rate =  $request->get('rate');
            $division2->introduce =  $request->get('introduce');
            $division2->advertise =  $request->get('advertise');
             $division2->condition =  $request->get('condition');
            $division2->address =  $request->get('address');
             $division2->national_ID=  $request->get('national');
             $division2->provinceId =  $request->get('province');
              $division2->districtId =  $request->get('district');
              $division2->worktime1 =  $request->get('worktime1');
              $division2->worktime2 =  $request->get('worktime2');
              $division2->holidays =  $request->get('holidays');
              $division2->review =  $request->get('review');
              $division2->yearBonus =  $request->get('yearBonus');
              $division2->welfare =  $request->get('welfare');
              $division2->otherWelfare =  $request->get('otherWelfare');
               $division2->status =  $request->get('status');
           $division2->save();
       $imp="Imp";
       $p_Introduce="Introduce";
       $p_advertise='Ad';
        if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                              return ($url = Session::get('ssClient')) 
   ? Redirect::to($url[2])->with('message', 'Succes! Division was updated') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'));
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                                return ($url = Session::get('ssDivision')) 
   ? Redirect::to($url[1])->with('message', 'Succes! Division was updated') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'));
                    }
                    else 
      return $this->show($id);
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
        $division = Division::find($id);
      $division->delete();
       // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                              return ($url = Session::get('ssClient')) 
   ? Redirect::to($url[1])->with('message', 'Succes! Division was delete()') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'));
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                                return ($url = Session::get('ssDivision')) 
   ? Redirect::to($url[0])->with('message', 'Succes! Division was delete') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'))->with('message', 'Succes! Pic was updated');
                    }                                    
  // end session
   return redirect('/client');
    }
       public function divisionSearch(Request $request)
    {
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
           DB::connection()->enableQueryLog();
         $divisionsrc=$request->input('divisionsrc_searchdiv');
         $companyname=$request->input('companysrc_searchdiv');
         $rate=$request->input('ratesrc');
         $introduce=$request->input('introducesrc');
         $status=$request->input('status');
         $statusM=$request->input('clientStatus');
        // $advertises=$request->input('advertisesrc');

         $pic_s=$request->input('pic_s');
         $national=$request->input('national');
         $provinceid=$request->input('province');
         $district=$request->input('district');
            $imp="Imp";
       $p_Introduce="Introduce";
       $p_advertise='Ad';
         $request->flash();



         ///// Search function
          $this->putSessDiv($request);
          $divQuery = Division::sortable()
          
           ->leftjoin('users', 'division.pic_s', '=', 'users.id')
           ->leftjoin('nation', 'division.national_ID', '=', 'nation.Id')
           ->leftjoin('client', 'client.id', '=', 'division.companyid')
           ->leftjoin('province', 'division.provinceId', '=', 'province.Id')
           ->leftjoin('district', 'division.districtId', '=', 'district.id')
            ->leftJoin('master as imp', function ( $join ) use  ($imp) {
            $join->on('division.rate','=','imp.code')
                 ->where('imp.type','=',$imp);
             })
            ->leftJoin('master as introduce', function ( $join ) use  ($p_Introduce) {
            $join->on('division.introduce','=','introduce.code')
                 ->where('introduce.type','=',$p_Introduce);
             })
              ->leftJoin('master as advertise', function ( $join ) use  ($p_advertise) {
            $join->on('division.advertise','=','advertise.code')
                 ->where('advertise.type','=',$p_advertise);
             })
               ->leftJoin('master as status', function ( $join ) use  ($statusM) {
            $join->on('division.status','=','status.val');
                 //->where('status.type','=',$statusM);
             })
           
          ->select('division.*', 'province.Name as provinceName','district.Name as districtName','nation.commonName as nationName','client.companyname as clientName','users.name as pics','client.code as clientCode','imp.name as rate2','introduce.name as introduceName','advertise.name as advertiseName','status.name as statusName')
            ->orderBy('clientName', 'ASC');
          if (!is_null($companyname)) {
            $divQuery->Where ('client.companyname','like','%' . $companyname . '%' );
          }
          if (!is_null($divisionsrc)) {
            $divQuery->Where ('division.divisionname','like','%' . $divisionsrc . '%' );
          }
         if (!is_null($rate)) {
            $divQuery->Where ('division.rate','like','%' . $rate . '%' );
          }
             if (!is_null($rate)) {
            $divQuery->Where ('division.rate','like','%' . $rate . '%' );
          }
             if (!is_null($introduce)) {
            $divQuery->Where ('division.introduce','like','%' . $introduce . '%' );
          }
           if (!is_null($status)) {
            
          }
          else{
            //->where(function ($query)use ($statusCK) {
             $divQuery->whereNull('division.status');
          }
          //    if (!is_null($advertises)) {
          //   $divQuery->Where ('division.advertises','like','%' . $advertises . '%' );
          // }
             if (!is_null($pic_s)) {
            $divQuery->Where ('division.pic_s','like','%' . $pic_s . '%' );
          }
           
             if (!is_null($national)) {
            $divQuery->Where ('division.national_ID','like','%' . $national . '%' );
          }
           
          if (!is_null($provinceid)) {
            $divQuery->Where ('division.provinceId','like','%' . $provinceid . '%' );
          }
          if (!is_null($district)) {
            $divQuery->Where ('division.districtId','like','%' . $district . '%' );
          }
          
          $division=$divQuery   ->sortable()       ->paginate(50);    
          
           // ->where(function ($query)use ($companyname) {
                
           //       $query->Where ('client.companyname','like','%' . $companyname . '%' );
           //          })
           //   ->where(function ($query)use ($divisionsrc) {
           //       $query->whereNull('division.divisionname')
           //       ->orWhere ('division.divisionname','like','%' . $divisionsrc . '%' );
           //          })
           // ->where(function ($query)use ($rate) {
           //       $query
           //       ->Where ('division.rate','like','%' . $rate . '%' );
           //          })
           //   ->where(function ($query)use ($introduce) {
           //       $query
           //       ->Where ('division.introduce','like','%' . $introduce . '%' );
           //          })
           //   ->where(function ($query)use ($advertises) {
           //       $query
           //       ->Where ('division.advertise','like','%' . $advertises . '%' );
           //          })
           //  ->where(function ($query)use ($pic_s) {
           //       $query
           //       ->Where ('division.pic_s','like','%' . $pic_s . '%' );
           //          })
           //   ->where(function ($query)use ($national) {
           //       $query
           //       ->Where ('division.national_ID','like','%' . $national . '%' );
           //          })
           //        ->where(function ($query)use ($provinceid) {
           //       $query
           //       ->Where ('division.provinceId','like','%' . $provinceid . '%' );
           //          })
           //             ->where(function ($query)use ($district) {
           //       $query
           //       ->Where ('division.districtId','like','%' . $district . '%' );
           //          })
           //   ->sortable()          
           // ->paginate(50);
           //        $query = DB::getQueryLog();
           //    $lastQuery = end($query);
           // dd(  $lastQuery);
            $national = Nation::all();
             $master=DB::table('master')
            ->get();
             $introduce=$master;
              $advertise=$master;
              $province = Province::all();

         $users = DB::table('users')->where('status','=','1')->orderBy('sort', 'ASC')->get();

          $districtDB=DB::table('district')
          ->where('ProvinceId','=',$provinceid)
          ->get();
            $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssDivision', $links);
          
            return view('division.searchDivision',  compact('division','pic','province','national','district','master','introduce','advertise','users','districtDB'));
      
    }
     public function clearSessDiv()
    {
        session()->put('forms.companysrc_searchdiv', '');
        session()->put('forms.divisionsrc', '');
        session()->put('forms.ssratesrc','');
        session()->put('forms.ssintroducesrc','');
        session()->put('forms.ssadvertisesrc','');
        session()->put('forms.sspic_s','');
        session()->put('forms.ssnational','');
        session()->put('forms.ssprovince','');
        session()->put('forms.ssdistrict','');
     
    }
    public function putSessDiv(Request $request)
    {
        session()->put('forms.ssratesrc', $request->get('ratesrc'));
        session()->put('forms.ssintroducesrc', $request->get('introducesrc'));
        session()->put('forms.ssadvertisesrc', $request->get('advertisesrc'));
        session()->put('forms.sspic_s', $request->get('pic_s'));
        session()->put('forms.ssnational', $request->get('national'));
        session()->put('forms.ssprovince', $request->get('province'));
        session()->put('forms.ssnational', $request->get('national'));
        session()->put('forms.ssdistrict', $request->get('district'));
    }

     public function clearSessInpDiv()
    {
         session()->put('forms.ssCode', '');
        session()->put('forms.ssIntroduceCode', '');
        session()->put('forms.ssAdCode','');
        session()->put('forms.ssUsID','');
        session()->put('forms.ssNaId','');
        session()->put('forms.ssProId','');
     
    }
    public function putSessInpDiv(Request $request)
    {
          session()->put('forms.ssCode', $request->get('rate'));
        session()->put('forms.ssIntroduceCode', $request->get('introduce'));
        session()->put('forms.ssAdCode', $request->get('advertise'));
        session()->put('forms.ssUsID', $request->get('pic_s'));
        session()->put('forms.ssNaId', $request->get('national'));
        session()->put('forms.ssProId', $request->get('province'));
    }


}
