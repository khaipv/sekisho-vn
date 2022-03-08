<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\District;
use App\Province;
use DB;

class ProvinceController extends Controller

{

	 public function showDistrict(Request $request)
    {     


        if ($request->ajax()) {
            $cities = DB::table('district')
            ->where("ProvinceId","=", $request->id)
             ->orderBy('Name', 'ASC')
            ->get();

           return response()->json($cities);
        }
    }
        public function showWard(Request $request)
    {     
            

        if ($request->ajax()) {
            $ward = DB::table('ward')
            ->where("DistrictID","=", $request->id)
            ->get();

           return response()->json($ward);
        }
    }
    
            public function showDivision(Request $request)
    {     
        if ($request->ajax()) {
            $division = DB::table('division')
            ->where("companyid","=", $request->id)
            ->get();
           return response()->json($division);
        }
    }
                public function showAddressByDiv(Request $request)
    {     
        if ($request->ajax()) {
            $division = DB::table('division')
            ->where("id","=", $request->id)
            ->get();
           return response()->json($division);
        }
    }
         public function showKind(Request $request)
    {     


        if ($request->ajax()) {
            $kinds = DB::table('can_kind')
            ->where("gcode","=", $request->id)
             ->orderBy('sort', 'ASC')
            ->get();
             
           return response()->json($kinds);
        }
    }

}
