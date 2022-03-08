<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;

use App\Tck_print;
use App\Exports\DataExport;
use Carbon\Carbon; 
use DB;
use App\Library\CommonTimeFunction;
use Illuminate\Cookie\CookieJar;
use Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CSVTimeController extends Controller
{
   public function importExport()
    {
       $user = Auth::user();
       return view('timesheet.importExport');
    }
    public function downloadExcel($type)
    {
      try {
  $data = DB::select("    SELECT staff. id,
   IFNULL(`firstName`,'') as firstName
   ,IFNULL(`midleName`,'') as midleName
   ,IFNULL(`lastName`,'') as lastName
   , IFNULL(`firstNameJ`,'') as firstNameJ
   ,IFNULL(`midleNameJ`,'') as midleNameJ
   ,IFNULL(`lastNameJ`,'')  as lastNameJ
   , tell tel,staff.mobile,staff.email,client.companyname 
   ,divv.divisionname,staff.created_at,staff.updated_at,staff.magazine
      from staff 
   left outer join division divv on staff.division_id=divv.id
      left outer join client  on client.id=divv.companyid
   where status='〇' ");
        
        $columTitle = CommonTimeFunction::generateColumnExportCsv();
        $fileName   = 'Candidates';
        $dataExcell       = CommonTimeFunction::generateDataExportCsv($columTitle, $data);
      return  Excel::create($fileName, function($excel) use ($dataExcell) {
            $excel->sheet('detail', function($sheet) use ($dataExcell) {
                $sheet->fromArray($dataExcell);
            });
        })->download($type);
        } catch (Exception $e) {
          dd($e);
      }

    }
    public function importExcel(Request $request)
    {
        if($request->hasFile('import_file')){
          $user = Auth::user();
          $ldate = date('YmdHis');
       if (is_null($user) ) {
           return  redirect('/');
          }
           $month = Carbon::createFromFormat('Y-m', $request->input('month'));
            $res=Tck_print::whereRaw("year(date)=". $month->year)
                         ->whereRaw("month(date)=". $month->month)
                         ->delete();
           $filename =  $user->id.$ldate;
             $uploadedFile = $request->file('import_file');
    // save to storage/app/photos as the new $filename
        Storage::disk('local')->putFileAs(
        'files/excel',
        $uploadedFile,
        $filename
      );
                    Excel::load($request->file('import_file')->getRealPath(), function ($reader) {
                    $users = Auth::user();
                         $userH = DB::table('tck_user')
                    ->leftjoin('users', 'tck_user.id','=','users.id')
                    ->leftjoin('tck_companies', 'tck_user.companycode','=','tck_companies.code')
                    ->selectRaw('tck_user.*,tck_companies.restS,restE,SUBTIME(restE,restS ) as rest')
                    ->where("tck_user.id","=",  $users->id)
                    ->first();
                     // DD($users)
                foreach ($reader->toArray() as $key => $row) {
                  if(!empty($row)) {
                      $attendance=null;
                      $leaving=null;
                      $workingTime=null;
                      if (!is_null($row['attendance'])) {
                        $attendance=$this->timesub($row['attendance']);
                      }
                       if (!is_null($row['leaving'])) {
                        $leaving=$this->timesub($row['leaving']);
                      }
                         if (!is_null($row['work_time'])) {
                        $workingTime=$this->timesub($row['work_time']);
                      }
                      // dd($row);
                     // dd($attendance);
                      $company= $userH->companyCode;
                       $item=Tck_print::updateOrCreate(
                          [
                            'code'=>substr($row['id'], 0,8),
                            'name'=>$row['name'],
                            'date'=> $this->datesub ($row['date']) ,
                            'attendance'=>$attendance,
                            'leaving'=>$leaving,
                            'attendance_ori' =>$attendance,
                            'leaving_ori' =>$leaving,
                            'workingTime'=>$workingTime,
                            'companyCode'=>$userH->companyCode,
                            'status'=>101,
                            'wt_code'=>$userH->whID,
                          ]
                          );
                          $item->save();
                    }
                }
            });
        }
        $request->session()->flash('alert-success', 'Your file successfully import in database!!!');
        return back();
    }

    private function datesub(String $strdate)
    {
      $to=0;
      if (strpos($strdate, '(') !== false) {
            $to=strpos($strdate, '(');
          $time = strtotime(substr($strdate, 0 ,$to-1));
           return $newformat = date('Y-m-d',$time);
           }
           return null;
    }
      private function timesub(String $strdate)
    {
      if (strpos($strdate, ':') !== false) {
             $time = strtotime(substr($strdate, 11 ,8));
         //   $newformat = time('HH:ii:ss',$time);
          return  gmdate('H:i', $time);
           }
           return null;
    }
    public function sekilogin(CookieJar $cookieJar,Request $request)
    {
       $temptable ='';
       $entry_no= $request->input('entry_no');
       $password= $request->input('password');
       if($entry_no=='SekiSh0'&& $password=='P@Dw0d')
       {
         $cookieJar->queue(cookie('cookieSDFDFA', $entry_no, 600));
       return view('importExport');
       }
         return view('mobile.sekilogin');
     }

           
}
