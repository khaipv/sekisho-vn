<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Staff;
use App\Province;
use App\Nation;
use DB;
use DateTime;
use Carbon\Carbon; 
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Orders;
use Hash;
class ProfileController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProfile()
    {

             $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
       
          // if(!Hash::check($oldPassword, Auth::user()->password)){

          // }
          
            
        return view('profile.editProfile' );

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
          //Validate
               $this->validate($request,[
          'name' => 'required' ,
          'userName' => 'required',
          'email' => 'required|email|max:255',
        'password_confirmation' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[0-9])(?=.*[A-Z]).*$/',
          
        ],[
          'name.required' => ' The Client name field is required.'
          
        ]);


                $oldPassword = $request->password;
        $newPassword = $request->password_confirmation;
        if(!Hash::check($oldPassword, Auth::user()->password)){
         dd("Wrong");
        }else{
            $user->password = Hash::make($newPassword);
            $user->name     = $request->name;
            $user->userName     = $request->userName;
            $user->email     = $request->email;
            $user->save(); 
           
        }
           return view('profile.detailProfile' );
       
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
    }
   
}
