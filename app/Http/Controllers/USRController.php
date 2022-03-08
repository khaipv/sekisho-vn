<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use App\Tck_user;
use App\Tck_user_leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
class USRController extends Controller
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
       
          if($user->role <1){
           return  redirect('/');
          }
          // $usrlogTCK=DB::table('tck_user')
          // ->where('tck_user.id', '=', $user->id)
          // ->first(); // dd($user);
          $usrLst=DB::table('users')
      
         // ->where('users.id', '=',$user->id)
           ->select('users.name','users.code','email', 'userName',
              'joinDate','leaveDate','users.id')
           ->orderBy('code','ASC')
          ->get(); 
         return view('usr.index', compact('usrLst'));
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
          
           $usrLstN = DB::table('users')
            
            ->select( 'users.name','users.code','id','email', 'userName',
              'joinDate','leaveDate')
             ->where(function ($query)use ($nameScr) {
               if (!is_null($nameScr)) {
                  $query->Where ('name','like','%' . $nameScr . '%' );


               }
                    })
             ->where(function ($query)use ($codeScr) {
                 if (!is_null($codeScr)) {
                 $query->Where ('users.code','like','%' . $codeScr . '%' );
               }
                    });
            
                 
              

                $usrLst=$usrLstN  ->orderBy('code','ASC')->get();  

           
         return view('usr.index', compact('usrLst'));
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
         $t = Carbon::now();
           $year=$t->year;
      $newTCKUsr= new Tck_user
     ([
         'name'=> $request->get('name'),
         'code' => $request->get('code'),

         'companyCode'=>$loginUsr->companyCode,
         'annualLeaveDate'=> 0,
          'annualLeaveDate'=> $this->fcn_Annual_dateCr($request->get('joinDate')),
           'role'=> 0,
          'whID'=> 1,
          'ma'=> 119,
          'mb'=> 1024,
          'year'=> $t->year,
          'companyCode' => 'CP0001',
          'depart' => '法人会社／HR',
                ]);
        $newTCKUsr->save();
         $newUsr= new User
     ([
         
         'name'=> $request->get('name'),
         'email'=> $request->get('email'),
         'userName'=> $request->get('userName'),
          'joinDate'=> $request->get('joinDate'),
         'leaveDate'=> $request->get('leaveDate'),
         'code' => $request->get('code'),
         'role'=> 0,
          'companyCode' => 'CP0001',
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
       $this->validateUDPNewUser( $request, $id); 
       $tblusers=DB::table('users')
       ->Where('users.code','=',$request->get('code'))
       ->first();   

       $tblUsersUdp=User::find($id);
      //  $masterUdp = Tck_user::findOrFail($id);
      // // $masterUdp->update($request->all());
      //    $masterUdp->code =  $request->get('code');
      
     
      //   $masterUdp->joinDate =  $request->get('joinDate');
      //   $masterUdp->leaveDate =  $request->get('leaveDate');
      
      //  $masterUdp->save();
       $tblUsersUdp->userName = $request->get('userName'); 
       $tblUsersUdp->email = $request->get('email'); 
       $tblUsersUdp->name = $request->get('name'); 
       $tblUsersUdp->joinDate =  $request->get('joinDate');
         $tblUsersUdp->leaveDate =  $request->get('leaveDate');
        $tblUsersUdp->save();
      
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
        $code=$userD ->code;

        if (!is_null($userD)) {
          $userD->delete();
        }
 
        $userTck=Tck_user:: Where('code','=',$code) ->get();
        foreach ($userTck as $key => $value) {
        $value->delete();
        }
        // if (!is_null($userTck)) {
        //   $userTck->delete();
        // }
      
           
        return back()->with('message', 'Successful- User was deleted');
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
          $this->validate($request,[
             'email' => 'unique:users|required|email',
        ],[
          'please input unique email'
        ]);
              $this->validate($request,[
             'code' => 'unique:users'
        ],[
          'please input unique code'
        ]);
       }

        public function validateUDPNewUser($request,$id)
       {
        
$this->validate($request,[
            'email' => 'unique:users,email,' . $id,
         'userName' => 'required|unique:users,userName,'. $id,
        ],[
          'user login, email  is unique'
        ]);
       }
  protected function fcn_Annual_dateCr($dateValue)
    {
        $time=strtotime($dateValue);
        $month=date("m",$time);
        $year=date("Y",$time);
         $t = Carbon::now();
        
         $annualLeave=0;
         if (  (( $t->year -$year)*12 +($t->month -$month) >2 ) &&  (( $t->year -$year)*12 +($t->month -$month) < 12 )  ) {
            if ($t->year -$year >0) {                
                   $annualLeave=$t->month -1 ;
            } elseif ($t->year == $year && $t->month - $month >=2) {
               $annualLeave=$t->month - $month;
            }
            if ($t->month ==12) {
                $annualLeave++;
            }
         } elseif (( $t->year -$year)*12 +($t->month -$month) >=12 ) {
            $annualLeave=12;
         }
         return $annualLeave;
    }
}