<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use App\Product;
use App\Staff;
use App\Exports\DataExport;
use App\Candidates;
use DB;
use App\Library\CommonFunction;
use Illuminate\Cookie\CookieJar;
use Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CSVController extends Controller
{
   public function importExport()
    {
       $user = Auth::user();
       if (is_null($user) ) {
                 return  redirect('/');
                }
       if ($user->userName=='Araki'||$user->userName=='user6'||$user->userName=='Ebata') {
        return view('pic.importExport');
       }
       
        return view('/');
       
    }
	 public function downloadExcelDiv($type)
    {
      
      try {

        
     
  $data = DB::select("   SELECT divs.code
,divs.divisionname
, client.companyname
, divs.mobile tel
,imp.name Importance
,advertise.name advertise
,introduce.name as introduce
,users.name
,divs.created_at
from division as divs 
left join users on divs.pic_s=users.id
left join nation on divs.national_ID = nation.Id
left join client on client.id = divs.companyid
left join province on divs.provinceId = province.Id
left join district on divs.districtId = district.id
left join master as imp on (divs.rate =imp.code and imp.type='Imp' )
left join master as advertise on (divs.advertise =advertise.code and advertise.type='Ad' )
left join master as introduce on (divs.introduce =introduce.code and introduce.type='Introduce' )
order by created_at desc ");
        
        $columTitle = CommonFunction::generateColumnExportCsvDiv();
        $fileName   = 'Division';
        $dataExcell       = CommonFunction::generateDataExportCsvDiv($columTitle, $data);
      return  Excel::create($fileName, function($excel) use ($dataExcell) {
            $excel->sheet('detail', function($sheet) use ($dataExcell) {
                $sheet->fromArray($dataExcell);
            });
        })->download($type);
        } catch (Exception $e) {
          dd($e);
        
      }

    }
     public function downloadExcelOrder($type)
    {
      
      try {

        
     
  $data = DB::select("      select   order.code
      ,   division.divisionname as divisionName  ,  client.companyname  
     ,  order.title ,  users.name as pic  ,  pro.name as progressName  ,order.orderDate,order.introduceDate, order.workingDate
     ,  type.name as typeName ,  status.name as statusName
    ,  jp.name as JapaneseLevel  ,sex.name as sex
  ,  eng.name as EnglishLevel,order.skill,indispensable,   waranty.name as warantyName   
      ,concat( (salaryFrom),'~',(salaryTo),unitSaFrom.code) as Salary 
      ,concat(introduceFee-partnerfee,units.name) as imcome
      ,order.created_at, warrantyPeriod,recruit_num,age,invoiceCK,invoiceDate
    from candidate.order
      left join   candidate. division on  order.divisionID = division.id 
      left join   candidate. client on   order.clientID    =    client.id  
            left join   candidate. users on   order.pic_s    =    users.id  
            left Join candidate.master as status  on  order.status    =    status.id  
       left Join candidate.master as pro on (order.progress=pro.code and pro.type=  progress  )
        left join   candidate. master as type  on  order.type    =    type.id  
            left join   candidate. master as jp   on  order.JLevel    =    jp.id  
            left join   candidate. master as eng  on  order.ELevel    =    eng.id  
            left join   candidate. master as waranty  on   order.warranty    =    waranty.id  
            left join   candidate. units as units on  order.unitFrom    =    units.id  
             left join   candidate. master as sex on  order.sex    =    sex.id  
            left join   candidate. units as unitSaFrom  on  order.unitSaFrom    =    unitSaFrom.id  
         where    progress in 
 (10,20,30,40 )
             
        ");
       //where progress in (10,20,30,40)
        $columTitle = CommonFunction::generateColumnExportCsvOrder();
        $fileName   = 'Orders';
        
         //dd($columTitle);
        $dataExcell       = CommonFunction::generateDataExportCsvOrder($columTitle, $data);
      return  Excel::create($fileName, function($excel) use ($dataExcell) {
            $excel->sheet('detail', function($sheet) use ($dataExcell) {
                $sheet->fromArray($dataExcell);
            });
        })->download($type);
        } catch (Exception $e) {
          dd($e);
        
      }

    }
	 public function downloadCandidate($type,$kind)
    {
      try {

     $string ="  SELECT  ca.code as code, concat(firstName, ' ',midleName,' ',lastName) as Name
, concat(firstNamej, ' ',midleNamej,' ',lastNamej) VName,ca.birth,ca.email,mobile, sex.name as sex,ca.married
,uni.eName as University,major.name as major,ca.graduates,birthP.Name as Birthplace
,curAdd.Name as curAdd,jp.name as Japan,en.name as English, wp.name as WorkPlace
,sources.name as sources,replace(ca.mandan,1,'○') as preview,mandanDate as previewDate
,ca.workcheck as entering, ca.workDate as enteringDate,ca.situation,ca.plan,''  as col100name
,staff.name as staffname,changeJob,readyTime,email.name as  mailstatus
,col1,col39
 FROM candidates  as ca left  join master as sex on  ca.sex=sex.id
left join universities uni on ca.university=uni.id
left join major on ca.majors=major.val
left join province2 as birthP on birthP.Id=ca.birthPlace
left join province2 as curAdd on curAdd.Id=ca.currentAdd
left join master as jp on jp.id=ca.jLevel
left join master as en on en.id=ca.eLevel
left join master as wp on wp.Id=ca.workPlace
left join master as email on email.id=ca.contact
left join master as sources on sources.Id=ca.source
left join users as staff on staff.id=ca.staff
where ca.id >=5000
order by ca.id asc

 ";
  // where contact is null or contact   in (151,152)
 ////where  contact <> 151 or contact is null 
  $data = DB::select($string);
        
        $columTitle = CommonFunction::generateColumnExportCsvCandi();
        $fileName   = 'Candidates';
		
        $dataExcell       = CommonFunction::generateDataExportCsvCandi($columTitle, $data);
      return  Excel::create($fileName, function($excel) use ($dataExcell) {
            $excel->sheet('detail', function($sheet) use ($dataExcell) {
                $sheet->fromArray($dataExcell);
            });
        })->download($type);
        } catch (Exception $e) {
          dd($e);
        
      }

    }
    public function downloadExcel($type,$kind)
    {
      
      try {

     $string ="    SELECT staff. id,
   IFNULL(`firstName`,'') as firstName
   ,IFNULL(`midleName`,'') as midleName
   ,IFNULL(`lastName`,'') as lastName
   , IFNULL(`firstNameJ`,'') as firstNameJ
   ,IFNULL(`midleNameJ`,'') as midleNameJ
   ,IFNULL(`lastNameJ`,'')  as lastNameJ
   , tell tel,staff.mobile,staff.email,client.companyname 
   ,divv.divisionname,staff.created_at,staff.updated_at,staff.magazine
   ,users.name AS pic
      from staff 
   left outer join division divv on staff.division_id=divv.id
      left outer join client  on client.id=divv.companyid
      left outer join users  on divv.pic_s=users.id
   where 1=1 ";
   if($kind=="all") { }
   elseif ($kind=="m1") {$string=$string." and magazine =1 ";}
   elseif ($kind=="m2") {$string=$string." and magazine =2 ";}
   $data = DB::select($string);
        
        $columTitle = CommonFunction::generateColumnExportCsv();
        $fileName   = 'Staff';
        $dataExcell       = CommonFunction::generateDataExportCsv($columTitle, $data);
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
           $filename =  $user->id.$ldate;
             $uploadedFile = $request->file('import_file');
    // save to storage/app/photos as the new $filename

        Storage::disk('local')->putFileAs(
        'files/excel',
        $uploadedFile,
        $filename
      );
                    Excel::load($request->file('import_file')->getRealPath(), function ($reader) {
                foreach ($reader->toArray() as $key => $row) {
                    if(!empty($row)) {
                       // dd($row['magazine']);
                       $item=Staff::updateOrCreate(
                          [
                          'id'=>$row['id'],
                          ],
                          [
                            'firstName'=>$row['firstname'],
                            'midleName'=>$row['midlename'],
                            'lastName'=>$row['lastname'],
                            'firstNameJ'=>$row['firstnamej'],
                            'midleNameJ'=>$row['midlenamej'],
                            'lastNameJ'=>$row['lastnamej'],
                            'tell'=>$row['tel'],
                            'mobile'=>$row['mobile'],
                            'email'=>$row['email'],
                            'magazine'=>$row['magazine'],
							 'PIC'=>$row['pic'],
                          ]
                          );
                          $item->save();
                    }
                }
            });
        }
        $request->session()->flash('alert-success', 'Youe file successfully import in database!!!');
        return back();
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