<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Master;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use DB;
use Session;
use App\Library\CommonFunction;
class ClientControler extends Controller
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
      $role= DB::table('users')
          ->leftjoin('role', 'users.id', '=', 'role.userID')
          ->where('users.id', '=', $user->id)
          ->select('role.rank')
          ->first();
          if (is_null($role)||$role->rank <1 ||is_null($role->rank)) {
           return view('logined');
          }
          $statusTousan=147;
           $client = Client::sortable()
            ->leftjoin('master', 'client.status', '=', 'master.val')
            ->where('client.code','<>','10000')
            ->where(function ($query)use ($statusTousan) {
             $query->whereNull('status');
           
                    })
            ->select('client.*','master.name as status')
            ->orderBy('id', 'DESC')
            ->sortable()
            ->paginate(50);
             //  create session for back function
               $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssClient', $links);
            // end create session
        return view("client.index")->with('client',$client);
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
            $master=DB::table('master') ->orderBy('sort', 'DESC')->get();
        return view('client.createclient',compact('master'));
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
  
        $this->validate($request,[
          'companyname' => 'required' ,

          'companyname' => 'unique:client' 
          
        ],[
          'name.required' => ' The Client name field is required.'
          
        ]);
    
          $maxnum = CommonFunction::getAutoInscrement("client");
         $maxcode=$maxnum+100000;
         $companycode=substr($maxcode, -5);
         $url=str_replace(['http://', 'https://', ],"",$request->get('url'));
         $client2 = new client([
          'code' => $companycode,
          'companyname' => $request->get('companyname'),
          'tel' => $request->get('tel'),
          'url' =>$url,
          'status' =>$request->get('master'),
         ]);
        $client2->save();
      return $this->show($companycode);
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
       $p_Introduce="Introduce";
       $p_advertise='Ad';
        $clientStatus='clientStatus';
          $client = DB::table('client')
           ->leftjoin('master','client.status','=','master.val')
          ->where('client.id','=',$id)
          ->select('client.*','master.name as status')->first();

           $division = DB::table('division')
          ->leftjoin('staff', 'division.pic', '=', 'staff.id')
           ->leftjoin('users', 'division.pic_s', '=', 'users.id')
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
                     ->leftJoin('master as status', function ( $join ) use  ($clientStatus) {
            $join->on('division.status','=','status.val')
                 ->where('status.type','=',$clientStatus);
             })
          ->select('division.*',  'staff.name as name2', 'staff.department as department', 'staff.email as email2','staff.tell as tell2','staff.status as status2' ,'introduce.name as introduceName','imp.name as rate2','advertise.name as advertiseName','users.name as pics','status.name as statusName')
          ->where("division.companyid","=", $id)
          ->paginate(50);

           $count =$division->count();
           // create session
           if (Session::has('ssClient')) {
                   $url = Session::get('ssClient');
                   $currentLink = URL::full(); 
                    array_push($url, $currentLink); 
                    Session::flash('ssClient', $url);
                    }
           // end create session
            return view('client.detailclient', compact('client','id','division','count'));
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
           // create session
         if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    }
                          $imp="Imp";
       $p_Introduce="Introduce";
       $p_advertise='Ad';
       $master=DB::table('master') ->orderBy('sort', 'DESC')->get();
           // end create session
       $client = DB::table('client')
            
          ->where('client.id','=',$id)
          ->select('client.*' )->first();
                  $division = DB::table('division')
          ->leftjoin('staff', 'division.pic', '=', 'staff.id')
           ->leftjoin('users', 'division.pic_s', '=', 'users.id')
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
          ->select('division.*',  'staff.name as name2', 'staff.department as department', 'staff.email as email2','staff.tell as tell2','staff.status as status2' ,'introduce.name as introduceName','imp.name as rate2','advertise.name as advertiseName','users.name as pics')
          ->where("division.companyid","=", $id)
          ->paginate(50);
            return view('client.editclient', compact('client','id','division','master'));
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
             $this->validate($request,[
          'companyname' => 'required'
        ],[
          'name.required' => ' The Client name field is required.'
          
          
        ]);

        $client2 = client::find($id);
         $url=str_replace(['http://', 'https://', ],"",$request->get('url'));
        try {
        $exception =  new \Exception(' ');

          
        } catch (Exception $e) {
          $client = client::find($id);
          $division = DB::table('division')
          ->leftjoin('staff', 'division.pic', '=', 'staff.id')
         // ->leftjoin('staff_sekisho', 'division.pic_s', '=', 'staff_sekisho.id')
          ->select('division.*',  'staff.name as name2', 'staff.department as department', 'staff.email as email2','staff.tell as tell2','staff.status as status2')
          ->where("division.companyid","=", $id)
          ->paginate(50);
            return view('client.editclient', compact('client','id','division'));          
        }

           $client2->companyname =  $request->get('companyname');
           $client2->status =  $request->get('status');
            $client2->url=$url;
        $client2->save();
        ///
         $client = client::find($id);
           $division = DB::table('division')
          ->leftjoin('staff', 'division.pic', '=', 'staff.id')
          ->leftjoin('users', 'division.pic_s', '=', 'users.id')
          ->select('division.*',  'staff.name as name2', 'staff.department as department', 'staff.email as email2','staff.tell as tell2','staff.status as status2','users.name as pics')
          ->where("division.companyid","=", $id)
          ->paginate(50);
              if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                    }
                    

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
      $client = client::find($id);
       $division = DB::table('division')
          ->join('client', 'client.id', '=', 'division.companyid')
          ->where('division.companyid','=',$id)
          ->select('division.name');
          if ( $division->count()>0) {
                if (Session::has('ssClient')) {
                    Session::keep('ssClient');
                              return ($url = Session::get('ssClient')) 
   ? Redirect::to($url[0])->with('message', 'Client has Divisions') // Will redirect 2 links back 
   : 
             view('pic.detailpic', compact('pic','id'));
                    }
          }
      $client->delete();
       return redirect('/client')->with('message', 'Succes! Client was deleted');
    }
    public function mySearch(Request $request)
    {
     $request->flash();
        $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         $companyname=$request->input('companynamesrc');
        $statusCK=$request->input('status');
        $client = Client::sortable()
        ->leftjoin('master', 'client.status', '=', 'master.val')
         ->where('client.code','<>','10000')
          
->where(function ($query)use ($companyname) {
    $query->whereNull('client.companyname')
          ->orWhere ('client.companyname','like','%' . $companyname . '%' );
})
->where(function ($query)use ($statusCK) {
  if (!is_null($statusCK)) {
  
  } else {
     $query->whereNull('client.status');
           
     
  }
    
})
            ->select('client.*','master.name as status')
            ->orderBy('id', 'asc')
            ->paginate(50);
            $master=DB::table('master') ->orderBy('sort', 'DESC')->get();
            //  create session for back function
               $links = session()->has('links') ? session('links') : [];
            $currentLink = URL::full(); // Getting current URI like 'category/books/'
            array_push($links, $currentLink); // Putting it in the beginning of links array
             //Session::flash('backUrl', $links);
            Session::flash('ssClient', $links);
            // end create session
        return view("client.index",compact('client','master'));
    }

    public function showClient($code)
    {
        $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
       $client = client::find($id);
           $division = DB::table('division')
          ->leftjoin('staff', 'division.pic', '=', 'staff.id')
           ->leftjoin('users', 'division.pic_s', '=', 'users.id')
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
          ->select('division.*',  'staff.name as name2', 'staff.department as department', 'staff.email as email2','staff.tell as tell2','staff.status as status2' ,'introduce.name as introduceName','imp.name as rate2','advertise.name as advertiseName','users.name as pics')
          ->where("division.code","=", $code)
          ->orderBy('id', 'desc')
          ->paginate(50);

           $count =$division->count();
            return view('client.detailclient', compact('client','id','division','count'));
    }
}