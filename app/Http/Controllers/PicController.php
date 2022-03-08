<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Staff;
use App\Province;
use App\Nation;
use App\Master;
use DB;
use DateTime;
use Carbon\Carbon; 
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Orders;
use App\Client;
use Session;
use Redirect;
use Illuminate\Support\Facades\URL;
class PicController extends Controller
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
             session()->put('forms.companysrc', '');
             session()->put('forms.divisionsrc','');
             session()->put('forms.picFirstsrc', '');
             session()->put('forms.picMidlesrc', '');
             session()->put('forms.picLastsrc','');
             $magazineType='magazine';
           $pic = Staff::sortable()
           ->leftjoin('province', 'staff.province', '=', 'province.Id')
           ->leftjoin('district', 'staff.district', '=', 'district.id')
           ->leftjoin('ward', 'staff.ward', '=', 'ward.id')
           ->leftjoin('nation', 'staff.country', '=', 'nation.id')
           ->leftjoin('division as div','div.id','=','staff.division_id')
           ->leftjoin('client','div.companyid','=','client.id')
            ->leftJoin('master as magazine', function ( $join ) use  ($magazineType) {
            $join->on('staff.magazine','=','magazine.code')
                 ->where('magazine.type','=',$magazineType);
             })
           ->select('staff.*','province.Name as provinceName','district.Name as districtName','ward.Name as wardName','nation.CommonName as nationName' ,'nation.Id as nationID','client.companyName as companyName','div.divisionname as divName','magazine.name as magazine')
           ->orderBy('lastName', 'ASC')
          ->orderBy('firstName', 'ASC')
            ->sortable()
          ->paginate(50);
          //session start
           $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssPic', $links);
          // sesion end
            return view('pic.searchPic', compact('pic'));
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
           $client =  Client::orderBy('companyname', 'ASC') ->get();
        $pic_s=DB::table('staff_sekisho')
         ->get();
         $province = Province::all();
         $national = Nation::all();
         $magazine=Master::all();
      
        // $branch=DB::table('division')
        // ->join('client','division.companyid','=','client.id')
        // ->where('division.id','=',$id)
        // ->select('division.divisionname','client.companyname')
        // ->first();
        return view('pic.createpic2',compact('client','pic_s','province','national','magazine'));
         
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

            $this->validatePic($request);
             $midleName='';
             if(!is_null($request->get('midleName')))
             {
              $midleName=$request->get('midleName');
             }
         $staff = new Staff([
         'division_id'=> $request->get('division_id'),
         'department'=> $request->get('department'),
         'position'=> $request->get('position'),
         'tell'=> $request-> get('tell'),
         'email'=> $request->get('email'),
        //  'birth'=>Carbon::createFromFormat('Y-m-d', $request->get('datep'))->format('Y-m-d'),
         'mobile'=> $request->get('mobile'),
         'status'=> $request->get('status'),
         'firstName'=> $request->get('firstName'),
         'midleName'=> $midleName,
         'lastName'=> $request->get('lastName'),
         'firstNameJ'=> $request->get('firstNameJ'),
           'midleNameJ'=> $request->get('midleNameJ').' ',
         'lastNameJ'=> $request->get('lastNameJ'),
         'add'=> $request->get('add'),
          'magazine'=> $request->get('magazine'),

            ]);
       //  dd($date, $request->get('ivbId'),  $request-> get('tel'));

        $staff->save();
        $id=$request->get('division_id');
       

             // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                              return ($url = Session::get('ssClient')) 
   ? Redirect::to($url[2])->with('message', 'Succes! Pic was updated') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'));
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                                return ($url = Session::get('ssDivision')) 
   ? Redirect::to($url[1])->with('message', 'Succes! Pic was updated') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'))->with('message', 'Succes! Pic was updated');
                    }                                    
  // end session
return $this->show($staff->id);
          // return back to division if session exit

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
          $magazineType='magazine';
           $pic = DB::table('staff')
           ->leftjoin('province', 'staff.province', '=', 'province.Id')
           ->leftjoin('district', 'staff.district', '=', 'district.id')
           ->leftjoin('ward', 'staff.ward', '=', 'ward.id')
           ->leftjoin('nation', 'staff.country', '=', 'nation.id')
           ->leftjoin('division', 'staff.division_id', '=', 'division.id')
            ->leftjoin('client', 'division.companyid', '=', 'client.id')
            
             ->leftJoin('master as magazine', function ( $join ) use  ($magazineType) {
            $join->on('staff.magazine','=','magazine.code')
                 ->where('magazine.type','=',$magazineType);
             })
           ->where("staff.id","=", $id)
           ->select('staff.*','province.Name as provinceName','district.Name as districtName','ward.Name as wardName','nation.CommonName as nationName' ,'nation.Id as nationID','division.divisionname as divisionName','client.companyname as clientName','magazine.name as magazine','staff.division_id as div_ID')
          ->first();
          // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    } else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }
                     if (Session::has('ssPic')) {
                    $url = Session::get('ssPic');
                   $currentLink = URL::full(); 
                    array_push($url, $currentLink); 
                    Session::flash('ssPic', $url);
                    }

          // end session
            return view('pic.detailpic', compact('pic','id'));
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
          $magazineType='magazine';
          $magazine=Master::all();
               $pic = DB::table('staff')
       
           ->leftjoin('province', 'staff.province', '=', 'province.Id')
           ->leftjoin('district', 'staff.district', '=', 'district.id')
           ->leftjoin('ward', 'staff.ward', '=', 'ward.id')
           ->leftjoin('nation', 'staff.country', '=', 'nation.id')
           ->leftjoin('division', 'staff.division_id', '=', 'division.id')
            ->leftjoin('client', 'division.companyid', '=', 'client.id')
            ->leftJoin('master as magazine', function ( $join ) use  ($magazineType) {
            $join->on('staff.magazine','=','magazine.code')
                 ->where('magazine.type','=',$magazineType);
             })
           ->where("staff.id","=", $id)
           ->select('staff.*','province.Name as provinceName','district.Name as districtName','ward.Name as wardName','nation.CommonName as nationName','division.divisionname as divisionName','client.companyname as clientName')
          ->first();
         $national = Nation::all();
          $magazine = Master::all();
         $province = Province::all();
          // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }
                     if (Session::has('ssPic')) {
                    Session::keep('ssPic');
                    }


         // end session
         return view('pic.editpic', compact('pic','id','pic','province','national','magazine'));

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
        if (  !is_null($request->get('firstName')) ) 
        { 
            $this->validate($request,['lastName' => 'required',
                                'tell' => 'required',
                                'email' => 'required|email'
                                      
        ],[ 
        ]);

            
        } else 
        {
             $this->validate($request,[
                'firstNameJ' => 'required',
                'lastNameJ' => 'required',
                'tell' => 'required',
                'email' => 'required|email'
                                      
        ],[ 
        ]);


        }
         if ($request->get('national')== 1) {
                        $this->validate($request,[
                                      'province' => 'required',
                                      'district' => 'required'
                                        ],[ 'name.required' => ' The first name field is required.'
        ]);
 
                       }   
    try {
        $exception =  new \Exception(' ');
        $staff = staff::find($id);
        $staff->tell= $request-> get('tell');
        $staff->department= $request-> get('department');
        $staff->position= $request-> get('position');
        $staff->email= $request->get('email');
        $staff->mobile= $request->get('mobile');
        $staff->status= $request->get('status');
        //$staff->birth=Carbon::createFromFormat('Y-m-d', $request->get('datep'))->format('Y-m-d');
        $staff->firstName= $request->get('firstName');
        $staff->midleName= $request->get('midleName');
        $staff->lastName= $request->get('lastName');
        $staff->firstNameJ= $request->get('firstNameJ');
        $staff->midleNameJ= $request->get('midleNameJ').' ';
        $staff->lastNameJ= $request->get('lastNameJ');
        $staff->add= $request->get('add');
        $staff->country= $request->get('national');
        $staff->province= $request->get('province');
        $staff->district= $request->get('district');
         $staff->magazine= $request->get('magazine');
        $staff->save();
        $magazineType='magazine';
           $pic = DB::table('staff')
           ->leftjoin('province', 'staff.province', '=', 'province.Id')
           ->leftjoin('district', 'staff.district', '=', 'district.id')
           ->leftjoin('ward', 'staff.ward', '=', 'ward.id')
           ->leftjoin('nation', 'staff.country', '=', 'nation.id')
            ->leftjoin('division', 'staff.division_id', '=', 'division.id')
            ->leftjoin('client', 'division.companyid', '=', 'client.id')
             
             ->leftJoin('master as magazine', function ( $join ) use  ($magazineType) {
            $join->on('staff.magazine','=','magazine.code')
                 ->where('magazine.type','=',$magazineType);
             })
           ->where("staff.id","=", $id)
           ->select('staff.*','province.Name as provinceName','district.Name as districtName','ward.Name as wardName','nation.CommonName as nationName' ,'nation.Id as nationID','client.companyname as clientName','division.divisionname as divisionName','magazine.name as magazine','staff.division_id as div_ID')
          ->first();
           // create sesion
            if (Session::has('ssPic')) {
                    Session::keep('ssPic');
                  }

              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                              return ($url = Session::get('ssClient')) 
   ? Redirect::to($url[2])->with('message', 'Succes! Pic was updated') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'));
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                                return ($url = Session::get('ssDivision')) 
   ? Redirect::to($url[1])->with('message', 'Succes! Pic was updated') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'));
                    }
                    else 
                    return ($url = Session::get('ssPic')) 
   ? Redirect::to($url[1])->with('message', 'Succes! Pic was updated') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'));
                
  // end session
          // return back to division if session exit


        }
    catch (Exception $e) {
    return view('pic.detailpic', compact('pic','id'));
        }
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
       
         $taff = Staff::find($id);
         $taff->delete();
               // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                              return ($url = Session::get('ssClient')) 
   ? Redirect::to($url[2])->with('message', 'Succes! Pic was deleted') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'));
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                                return ($url = Session::get('ssDivision')) 
   ? Redirect::to($url[1])->with('message', 'Succes! Pic was deleted') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'))->with('message', 'Succes! Pic was deleted');
                    }                                    
  // end session
   return redirect('/client');
          // return back to division if session exit
    //   return redirect('/client');
    }
    public function addnew(Request $request)
    {
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
        $pic_s=DB::table('staff_sekisho')
         ->get();
         $province = Province::all();
         $national = Nation::all();
         $magazine=Master::all();
        $id=$request->get('division2');
        $branch=DB::table('division')
        ->join('client','division.companyid','=','client.id')
        ->where('division.id','=',$id)
        ->select('division.divisionname','client.companyname')
        ->first();
          // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }

         // end session
                return view('pic.createpic',compact('id','pic_s','province','national','branch','magazine'));

       
    }
    public function showDiv($id)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
          $imp="Imp";
       $p_Introduce="Introduce";
       $p_advertise='Ad';
       $division = DB::table('division')
           ->leftjoin('users', 'division.pic_s', '=', 'users.id')
           ->leftjoin('nation', 'division.national_ID', '=', 'nation.Id')
           ->leftjoin('client', 'client.id', '=', 'division.companyid')
           ->leftjoin('province', 'division.provinceId', '=', 'province.Id')
           ->leftjoin('district', 'division.districtId', '=', 'district.id')
           ->leftjoin('ward', 'division.wardId', '=', 'ward.id')
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
          ->select('division.*', 'province.Name as provinceName','district.Name as districtName','nation.commonName as nationName','ward.Name as wardName','client.companyname as clientName','users.name as name_s','client.code as clientCode','imp.name as rate2','introduce.name as introduceName','advertise.name as advertiseName')
          ->first();
           $staff = DB::table('staff')
          ->where("staff.division_id","=", $id)
          ->paginate(50);
          //order
          // Order list
               $order = Orders::sortable()
            ->leftjoin('division', 'order.divisionID', '=', 'division.id')
            ->leftjoin('client', 'order.clientID', '=', 'client.id')
            ->leftjoin('users', 'order.pic_s', '=', 'users.id')
            ->leftJoin('master as status', 'order.status', '=', 'status.id')
            ->leftjoin('master as pro', 'order.progress', '=', 'pro.id')
            ->leftjoin('master as type', 'order.type', '=', 'type.id')
            ->leftjoin('master as jp', 'order.JLevel', '=', 'jp.id')
            ->leftjoin('master as eng', 'order.ELevel', '=', 'eng.id')
            ->leftjoin('master as waranty', 'order.warranty', '=', 'waranty.id')
            ->select('order.*', 'division.divisionname as divisionName','client.companyname as clientName','users.name as pics','status.name as statusName','pro.name as progressName','type.name as typeName','jp.name as jpName','eng.name as engName','waranty.name as warantyName','pro.name as progressName')
            ->where("divisionID","=", $id)
            ->orderBy('id', 'asc')
             ->sortable()
            ->paginate(50);
            $delCount=$staff->count()+$order->count();
            return view('division.detaildivision', compact('division','id','staff','order','delCount'));
    }
     public function picSearch(Request $request)
    {
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         $divisionsrc=$request->input('divisionsrc');
         $companyname=$request->input('companysrc');
         $picsFist=$request->input('picFirstsrc');
         $picsMidle=$request->input('picMidlesrc');
         $picsLast=$request->input('picLastsrc');
         $magazine=$request->input('magazine');
         $email=$request->input('emailsrc');
         $phone=$request->input('phonesrc');
       
       


          $request->flash();

         ///// Search function
          $magazineType='magazine';
          $pic = DB::table('staff')
           ->leftjoin('province', 'staff.province', '=', 'province.Id')
           ->leftjoin('district', 'staff.district', '=', 'district.id')
           ->leftjoin('ward', 'staff.ward', '=', 'ward.id')
           ->leftjoin('nation', 'staff.country', '=', 'nation.id')
           ->leftjoin('division as div','div.id','=','staff.division_id')
           ->leftjoin('client','div.companyid','=','client.id')
           ->leftJoin('master as magazine', function ( $join ) use  ($magazineType) {
                      $join->on('staff.magazine','=','magazine.code')
                           ->where('magazine.type','=',$magazineType);
                       })
           ->select('staff.*','province.Name as provinceName','district.Name as districtName','ward.Name as wardName','nation.CommonName as nationName' ,'nation.Id as nationID','client.companyName as companyName','div.divisionname as divName')
            ->where(function ($query)use ($companyname) {
                 $query->whereNull('client.companyname')
          ->orWhere ('client.companyname','like','%' . $companyname . '%' );
                    })
            ->where(function ($query)use ($divisionsrc) {
                 $query->whereNull('div.divisionname')
          ->orWhere ('div.divisionname','like','%' . $divisionsrc . '%' );
                    })
             ->where(function ($query)use ($picsFist) {
                 $query->whereNull('firstName')
          ->orWhere ('firstName','like','%' . $picsFist . '%' );
                    })
           ->where(function ($query)use ($picsMidle) {
                $query->whereNull('staff.midleName')
          ->orWhere ('staff.midleName','like','%' . $picsMidle . '%' );
                    })
           ->where(function ($query)use ($picsLast) {
                 $query->whereNull('staff.lastName')
          ->orWhere ('staff.lastName','like','%' . $picsLast . '%' );
                    })
            ->where(function ($query)use ($magazine) {
                 $query->whereNull('staff.magazine')
          ->orWhere ('staff.magazine','like','%' . $magazine . '%' );
                    })
             ->where(function ($query)use ($email) {
                 $query->whereNull('staff.email')
          ->orWhere ('staff.email','like','%' . $email . '%' );
                    })
          ->where(function ($query)use ($phone) {
                   if (!is_null($phone)) {
                 $query 
          ->orWhere ('staff.mobile','like','%' . $phone . '%' );
                    }
                    })
      
           ->orderBy('lastName', 'ASC')
          ->orderBy('firstName', 'ASC')
          ->paginate(50);
            $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssPic', $links);
            return view('pic.searchPic', compact('pic'));
    }
    public function validatePic($request)
    {
       // create sesion
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    }else if (Session::has('ssDivision')) {
                      Session::keep('ssDivision');
                    }

         // end session

                  $this->validate($request,[
                'firstName' => 'required',
                'lastName' => 'required',
                'tell' => 'required',
                'division_id'=> 'required',
                'email' => 'required|email',
                'magazine'=> 'required',
                                      
        ],[ 
        ]);
    }
}
