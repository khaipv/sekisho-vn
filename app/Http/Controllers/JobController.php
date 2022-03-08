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
use Illuminate\Support\Facades\Auth;
class JobController extends Controller
{
   public function index()
    {
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
          $month=6;
        $code=1;
  
  
          $temptable ='';
      

  // dd($temptable);
          
         return view('job.jobfairWeb', compact('temptable'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = client::find($id);
      $division  = DB::table('division')
          ->where("companyid","=", $id)
          ->paginate(10);
            return view('client.editclient', compact('client','id','division'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
  

     
}
