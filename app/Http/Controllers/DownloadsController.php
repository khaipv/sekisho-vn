<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Master;
use App\Division;
use DB;
use App\User;
use Illuminate\Support\Facades\Auth;
class DownloadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

      public function download($file) {
 
        $file_path = public_path("img/".$file);
    return response()->download($file_path);
  }
    
      
}