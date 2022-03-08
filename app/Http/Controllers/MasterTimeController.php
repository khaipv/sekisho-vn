<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tck_master;
use App\tck_master_type;
use DB;
use App\User;
use Illuminate\Support\Facades\Auth;
class MasterController extends Controller
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
      
           $master =Tck_master::sortable()
            ->orderBy('id', 'desc')
            ->sortable()
            ->paginate(10);
              $masterType = DB::table('tck_master_type') ->get();
               $masterTypeS=$masterType;
       
         return view('master.index', compact('master','masterType','masterTypeS'));
    }
        public function masterSearch(Request $request)
    {
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
           $masterName=$request->input('nameScr');
           $masterType=$request->input('masterTypeScr');
           $master =Tck_master::sortable()
            
            ->orderBy('type', 'desc')
            ->select('tck_master.*')
             ->where(function ($query)use ($masterName) {
                
                 $query->Where ('tck_master.name','like','%' . $masterName . '%' );
                    })
             ->where(function ($query)use ($masterType) {
                
                 $query->Where ('tck_master.type','like','%' . $masterType . '%' );
                    })
            ->paginate(10);
              $masterType = DB::table('tck_master_type') ->get();
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
      $master= new Tck_master
     ([
         'name'=> $request->get('name'),
         'type' => $request->get('type'),
         'sort'=> $request->get('sort'),
         'code'=> $request->get('code'),
          'udpUser'=> $user ->userName,
                ]);
        $master->save();
          return back();
    }

    /**
     * Display the specified resource.
     *Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
     
      dd($request->input('code'));
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

       $masterUdp =Tck_master::findOrFail($id);
      // $masterUdp->update($request->all());
         $masterUdp->code =  $request->get('code');
       $masterUdp->name =  $request->get('name');
       $masterUdp->sort =  $request->get('sort');
       $masterUdp->udpUser=$user->userName;
       $masterUdp->save();
       $master=DB::table('master')->where('type', $masterUdp->type)
            ->orderBy('id', 'desc')
            ->paginate(10);
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
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
        $masterUdp =Tck_master::findOrFail($id);
        $masterUdp->delete();

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
          ->paginate(10);

           $count =$division->count();
            return view('client.detailclient', compact('client','id','division','count'));
    }
}