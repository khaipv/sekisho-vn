<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use App\Tck_user;
use App\Tck_annualleave;
use App\Tck_user_leave;
use Carbon\Carbon;
use App\Tck_dayoffb;
use App\Library\CommonTimeFunction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
class USRAController extends Controller
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
    const OT_CR_SPECIAL=203;
     const OT_DELETE_CP=404;
         const MAN_DENY_1=103;
    const MAN_DEN_2=104;
    const MAN_APP_1=105;
    const MAN_APP_2=106;
        const OFF_TYPE_MOR=501;
    const OFF_TYPE_AFT=502;
    const OFF_TYPE_ALL=503;
    public function index()
    {
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
       
          if($user->role <= 0){
           return  redirect('/');
          }
          $usrlogTCK=DB::table('users')
          ->where('users.id', '=', $user->id)
          ->first(); // dd($user);
           $t = Carbon::now();
           $year=$t->year;
          $usrLst=DB::table('users')
          ->join('tck_user','users.code','=','tck_user.code')
          ->select('tck_user.id','users.name','tck_user.year','users.code','tck_user.annualLeaveDate','users.leaveDate')
          ->where('users.companyCode', '=', $usrlogTCK->companyCode)
           ->where('users.role','<=',1)
           ->where('tck_user.year','=',$year)
           ->where(function ($query) use ($t) {
    $query->whereNull('users.leaveDate')
          ->orWhere('users.leaveDate','>',$t);
})
          ->get(); 

  ////  count usr start  tck_user_leave
                  $dayoffsQuery=Tck_dayoffb::query();
                     $dayoffsQuery ->join('tck_dayoff', 'tck_dayoffb.doID','=','tck_dayoff.id')
                     ->selectRaw("tck_dayoffb.date,tck_dayoffb.amID,tck_dayoffb.pmID,year(tck_dayoffb.date) as year,tck_dayoff.status as status ,usrCode")
                   
                     ->where("tck_dayoff.status","<>",self::OT_DELETE_CP)
                     ->where("tck_dayoff.status","<>",self::MAN_DENY_1)
                     ->where("tck_dayoff.status","<>",self::MAN_DEN_2)
                     
                        
                    ->orderBy('year','code');
                     $dayoffList=$dayoffsQuery ->get();
                     // $dayoffNumber in month and dayoff number next month
                     $annualLeaveLST=array();
                     foreach ($usrLst as $key => $usryear) {
                       $annualLeaveDateSum=$dayoffPreNumber=0;
                            foreach ($dayoffList as $key => $value) {
                         if ( $usryear->year== $value->year &&  $usryear->code== $value->usrCode && $value->amID == -1) {
                           $annualLeaveDateSum +=0.5;
                         
                         }
                         if (  $usryear->year== $value->year &&  $usryear->code== $value->usrCode && $value->pmID == -1) {
                           
                           $annualLeaveDateSum +=0.5;
                         }
                     }
                     $usrarr= array( (string) 'a'.$usryear->code.$usryear->year =>$annualLeaveDateSum , );
                     if ($usryear->code=='21212') {
                   //  dd($usrarr);
                     }
                       $annualLeaveLST= array_merge ($annualLeaveLST,$usrarr) ;
                       
                     }
                  
                 
                   
                
                // end cout 


         return view('usr.annual', compact('usrLst','annualLeaveLST','year'));
    }
        public function usrSearch(Request $request)
    {
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
           $request->flash();
           $t = Carbon::now();
           //$yearScr=$t->year;

           $nameScr=$request->input('nameScr');

           $codeScr=$request->input('codeScr');
         
            $yearScr=$request->input('yearScr');
          
           $usrLst = DB::table('users')
             ->join('tck_user','users.code','=','tck_user.code')

            ->select('tck_user.*','users.name','users.leaveDate')
             ->where('users.role','<=',1)
             ->where(function ($query)use ($nameScr) {
               if (!is_null($nameScr)) {
                  $query->Where ('users.name','like','%' . $nameScr . '%' );


               }
                    })
             ->where(function ($query)use ($codeScr) {
                 if (!is_null($codeScr)) {
                 $query->Where ('tck_user.code','like','%' . $codeScr . '%' );
               }
                    })
               ->where(function ($query)use ($yearScr) {
                   if (!is_null($yearScr)) {
                 $query->Where ('tck_user.year','=', $yearScr );
               }
                    }) ->get();
                 
             


                ////  count usr start
                  $dayoffsQuery=Tck_dayoffb::query();
                     $dayoffsQuery ->join('tck_dayoff', 'tck_dayoffb.doID','=','tck_dayoff.id')
                     ->selectRaw("tck_dayoffb.date,tck_dayoffb.amID,tck_dayoffb.pmID,year(tck_dayoffb.date) as year,tck_dayoff.status as status ,usrCode")
                   
                     ->where("tck_dayoff.status","<>",self::OT_DELETE_CP)
                     ->where("tck_dayoff.status","<>",self::MAN_DENY_1)
                     ->where("tck_dayoff.status","<>",self::MAN_DEN_2)
                     
                         ->where(function ($query)use ($yearScr) {
           
           if (!is_null($yearScr)) {
                    $query->whereRaw("year(tck_dayoffb.date) =" .   $yearScr);
           }
               
                    })  
                    ->orderBy('year','code');
                     $dayoffList=$dayoffsQuery ->get();
                     // $dayoffNumber in month and dayoff number next month
                     $annualLeaveLST=array();
                     foreach ($usrLst as $key => $usryear) {
                       $annualLeaveDateSum=$dayoffPreNumber=0;
                            foreach ($dayoffList as $key => $value) {
                         if ( $usryear->year== $value->year &&  $usryear->code== $value->usrCode && $value->amID == -1) {
                           $annualLeaveDateSum +=0.5;
                         
                         }
                         if (  $usryear->year== $value->year &&  $usryear->code== $value->usrCode && $value->pmID == -1) {
                           
                           $annualLeaveDateSum +=0.5;
                         }
                     }
                     $usrarr= array( (string) 'a'.$usryear->code.$usryear->year =>$annualLeaveDateSum , );
                       $annualLeaveLST= array_merge ($annualLeaveLST,$usrarr) ;
                     }
                  
                  

                // end cout 

           
         return view('usr.annual', compact('usrLst','annualLeaveLST'));
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
       // if (is_null($user) && $user->role>0 ) {
       //     return  redirect('/');
       //    }
        $loginUsr=Tck_user::findOrFail($user->id);  
        $this->validateInputUser($request); 
      $newTCKUsr= new Tck_user
     ([
         'name'=> $request->get('name'),
         'code' => $request->get('code'),

         'companyCode'=>$loginUsr->companyCode,
         'annualLeaveDate'=> $request->get('annualLeave'),
           'joinDate'=> $request->get('joinDate'),
              'leaveDate'=> $request->get('leaveDate'),
           'role'=> 0,
          'WhID'=> 1,
          'ma'=> 3,
          'mb'=> 2,
                ]);
        $newTCKUsr->save();
         $newUsr= new User
     ([
         
         'name'=> $request->get('name'),
         'userName'=> $request->get('userName'),
         'code' => $request->get('code'),
         'role'=> 0,
         'password'=>   bcrypt($request->get('password')),
       ]);   
          $newUsr->save();

          return back()->with('message', 'Successful- User was inputed');
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
      if (Input::get('deny')) {
       return back();
      } elseif (Input::get('approve')) {
        # code...
      

       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
       // dayoffb
      $annualLeaveDate=$request->get('annualLeaveDate');
      
      $offdays=$request->get('offdays');
     //  $this->validateUDPUser( $request, $id); 
       $udpAnnual =  Tck_user::find($request->get('id'));
       $newAnnual=$request->get('annualLeaveDateI') -$offdays;   
       
       $udpAnnual->annualLeaveDate=   $newAnnual;
       $udpAnnual->save();
       CommonTimeFunction::fcn_synAnnual();
      
        return back()->with('message', 'Successful- User was updated');
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
      try {
        
     
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
        
        $userD = User::findOrFail($id); 

        if (!is_null($userD)) {
          $userD->delete();
        }

        $userTck=Tck_user::findOrFail($id);
        if (!is_null($userTck)) {
          $userTck->delete();
        }
        
          
        return back()->with('message', 'Successful- User was deleted');;
         } catch (Exception $e) {
        
      }

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
     // 
       public function validateUDPUser($request,$id)
       {
         $this->validate($request,[
             'userName' => 'required|userName|unique:users,userName,'.$id,
        ],[
          'userName was duplicated'
        ]);
       }
       public function validateInputUser($request)
       {
         $this->validate($request,[
             'userName' => 'required|unique:users,userName',
        ],[
          'user login was duplicated'
        ]);
       }
       public function getdayoffbByYear($yearScr)
       {
                     $dayoffsQuery=Tck_dayoffb::query();
                     $dayoffsQuery ->join('tck_dayoff', 'tck_dayoffb.doID','=','tck_dayoff.id')
                     ->selectRaw("tck_dayoffb.date,tck_dayoffb.amID,tck_dayoffb.pmID,year(tck_dayoffb.date) as year,tck_dayoff.status as status ,usrCode")
                   
                     ->where("tck_dayoff.status","<>",self::OT_DELETE_CP)
                     ->where("tck_dayoff.status","<>",self::MAN_DENY_1)
                     ->where("tck_dayoff.status","<>",self::MAN_DEN_2)
                     
                         ->where(function ($query)use ($yearScr) {
           
           if (!is_null($yearScr)) {
                    $query->whereRaw("year(tck_dayoffb.date) =" .   $yearScr);
           }
               
                    })  
                    ->orderBy('year','code');
                     $dayoffList=$dayoffsQuery ->get();
                     return  $dayoffList;
       }
}