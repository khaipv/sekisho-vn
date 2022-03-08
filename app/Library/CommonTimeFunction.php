<?php

namespace App\Library;


use DateTime;
use Lang;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

use App\Tck_master;
use App\Tck_dayoff;
use App\Tck_dayoffb;
use App\Tck_user;
use App\Division;
use App\Tck_dayyear;
use App\Tck_annualleave;
use App\Tck_workinghours;
use App\Tck_overtime;
use App\Tck_user_leave;
class CommonTimeFunction
{
    const STATUS_PASSWORD_NOT_CORRECT = 401;
    const STATUS_VALIDATE_ERROR = 422;
    const STATUS_FINISH_WORKED = 409;
    
    /// const overtime
     const OT_CR_STATUS  =101; 
    const OT_CP_STATUS  =402; 
    const OT_CR_CODE  =401; 
    const LV_CR_CODE  =405; 
    const OT_CR_ANNUAL=201;
    const OT_CR_CP=202;
    const OT_CR_SPECIAL=203;
    const OT_CR_UNP=204;
    const OT_DELETE_CP=404;

     const MAN_DENY_1=103;
    const MAN_DENY_2=104;
    const MAN_APP_1=105;
    const MAN_APP_2=106;
     const OT_EDIT=108;
     const TERM_MOR=501;
     const TERM_AFT=502;
     const TERM_ALL=503;

    /*
     * key cache
     */
    const KEY_CACHE = 'load-data';

    public static function autoIncrement($lenght)
    {
        for ($i = 0; $i < $lenght; $i ++) {
            yield $i;
        }
    }

    /**
     * load data
     * @return array
     */
    

    /**
     * validate integer
     * @param string
     * @return boolean
     */
    public static function validateNumber($arrayValue, $integer = null)
    {
       
        return true;
    }

    /**
     * validate string
     * @param string
     * @param int
     * @return boolean
     */
    public static function validateString($value, $lenghtMax)
    {
        $value = trim($value);
        $lenghtValue = strlen($value);
        if ($lenghtValue > $lenghtMax || $lenghtValue == 0) {
            return false;
        }
        return true;
    }

    /**
     * validate date
     * @param string
     * @return boolean
     */
    public static function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * validate hour
     * @param string
     * @return boolean
     */
    public static function validateHour($hour)
    {
        $h = DateTime::createFromFormat('H:i', $hour);
        return $h && $h->format('H:i') === $hour;
    }


    /**
     * validate time journey
     * @param string
     * @return boolean
     */
    public static function validateTimeJourney($hour)
    {
        $fullTime = DateTime::createFromFormat('Y-m-d H:i:s', $hour);
        $h = DateTime::createFromFormat('Y-m-d H:i', $hour);
        if (($fullTime && $fullTime->format('Y-m-d H:i:s') === $hour) ||
            ($h && $h->format('Y-m-d H:i') === $hour)
        ) {
            return true;
        }
        return false;
    }

    /**
     * validate time
     * @param string
     * @return boolean
     */
    public static function validateTime($startHour, $finishHour)
    {
        if (strtotime($finishHour) < strtotime($startHour)) {
            return false;
        }
        return true;
    }

    /**
     * check isset value
     * @param array
     * @param array
     * @return boolean
     */
    public static function checkIsset($data, $arrayField)
    {
        foreach ($arrayField as $value) {
            if (!isset($data[$value])) {
                return true;
            }
        }
        return false;
    }
            public static function getArraycodes ($arrayData)
    {
        $array = array();
        foreach ($arrayData as $arrayData) {
           array_push($array,$arrayData['id']);
        }
        return $array;
    }

    /**
     * get data export csv
     * @param array
     * @return array
     */


    /**
     * generate column export Csv
     * @return array
     */


    /**
     * generate data for export csv
     * @param  array $columns
     * @param  array $journeys
     * @return array
     */
   

    /**
     * check data download csv
     * @param  array $data
     * @return array
     */


    /**
     * clear all cache
     */

    /**
     * clear cache item
     * @param  string $key
     */

    /**
     * generate file name download csv
     * @param  array $data
     * @return string
     */


    /**
     * random string
     * @param int
     * @return string
     */
    
    public static function randomString($length) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
    public static function getAutoInscrement($table)
    {
              $statement = DB::select("SHOW TABLE STATUS LIKE '".$table."'");
      $maxnum = $statement[0]->Auto_increment;
      return  $maxnum;
    }
     public static function generateColumnExportCsv()
    {
        return array(
            'id'                    => 'id',
        'firstName'              => 'firstName',
        'midleName'              => 'midleName',
        'lastName'              => 'lastName',
        'firstNameJ'              => 'firstNameJ',
        'midleNameJ'              => 'midleNameJ',
        'lastNameJ'              => 'lastNameJ',
        'tel'              => 'tel',
        'mobile'              => 'mobile',
        'email'              => 'email',
        'companyname'              => 'companyname',
        'divisionname'              => 'divisionname',
        'created_at'              => 'created_at',
        'updated_at'              => 'updated_at',
         'magazine'              => 'magazine',
  
   
         
        );
    }
     public  static function generateDataExportCsv($columns, $arrDatas)
    {
        $row_order = 0;
        $data = [];
        if ($arrDatas) {
            foreach ($arrDatas as $key => $arrData) {

               $row_order++;
               $row = [];
                 $row[$columns['id']]         = $arrData->id;
                 $row[$columns['firstName']]         = $arrData->firstName;
                 $row[$columns['midleName']]         = $arrData->midleName;
                 $row[$columns['lastName']]         = $arrData->lastName;
                 $row[$columns['firstNameJ']]         = $arrData->firstNameJ;
                 $row[$columns['midleNameJ']]         = $arrData->midleNameJ;
                 $row[$columns['lastNameJ']]         = $arrData->lastNameJ;
                 $row[$columns['tel']]         = $arrData->tel;
                 $row[$columns['mobile']]         = $arrData->mobile;
                 $row[$columns['email']]         = $arrData->email;
                 $row[$columns['companyname']]         = $arrData->companyname;
                 $row[$columns['divisionname']]         = $arrData->divisionname;
                 $row[$columns['created_at']]         = $arrData->created_at;
                 $row[$columns['updated_at']]         = $arrData->updated_at;     
                 $row[$columns['magazine']]         = $arrData->magazine;       
                $data[]                         = $row;
            }
        } else {
            foreach ($columns as $key => $value) {
                $row[$columns[$key]] = null;
            }
            $data[] = $row;
        }
        return $data;
    }
        public  static  function checkHoliday ($from,$to)
    {
      if (!isset($to)) {
        $to=$from;
      }
    $tck_dayyear=DB::table('tck_dayyear')
    ->where('date','>=',$from)
     ->where('date','<=',$to) 
      ->where('typeName','=','WO') 

    
    ->get();
    $result=$tck_dayyear->keyBy('date');
    
    return $result;
    }
        // new create Dayoff start
        public static function createNewDayOff($request )
    {
     $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
              $dayOffLSTs=CommonTimeFunction::checkHoliday($request->get('dateDOFrom'),$request->get('dateDOTo'));
             
                $from =  Carbon::parse($request->get('dateDOFrom'));
                $now = Carbon::now();
                       $auUser;
       // if ($from->year != $now->year) {
       //    $auUserTemp = DB::table('tck_user_leave')
       //             ->select('tck_user_leave.*')
       //              ->where("idCode","=",  $user->id)
                  
       //              ->where("year","=", $from->year)
       //              ->first();

       //              $auUser=Tck_user_leave::findOrFail($auUserTemp->idCode);
                   
       //             }  else  {
       //               $auUser=Tck_user::findOrFail($user->id);
       //             }   
              $auUserTemp = DB::table('tck_user')
                   ->select('tck_user.*')
                    ->where("code","=",  $user->code)
                  
                    ->where("year","=", $from->year)
                    ->first();  
                    $auUser=Tck_user::findOrFail($auUserTemp->id);       
                  
          // create tck_dayoff get ID of dayoff
           $doID = DB::table('tck_dayoff')->insertGetId(
        [ 'fromDate'  =>$request->get('dateDOFrom'),
          'fromTerm'  =>$request->get('termFrom'),
          'toDate'=>$request->get('dateDOTo'),
          'toTerm'=>$request->get('termTo'),
          'note'=>$request->get('note'),
          'status'=>self:: OT_CR_STATUS,
          'usrCode'=>$auUser->code,
          'type' =>$request->get('doType'),
          'created_at' => Carbon::now()->toDateTimeString(),
            ]
          );       
            $dateOTlist=tck_overtime::where('usrID','=',$auUser->code)
      ->where('status','<>',self::OT_DELETE_CP)
      ->where('status','<>',self::MAN_DENY_1)
      ->where('status','<>',self::MAN_DENY_2)
      ->whereRaw('month(date)='.$from->month.' and year(date)='.$from->year.'  and statusCP=402 and datenum >0 ')
      ->orderBy('date','ASC')
      ->get(); 
           /// If Annual leave
           $i=0;
            foreach ($dayOffLSTs as $key => $value) {
              $i++;
              $date=$value->date;
              // Xet ngay dau tien neu la nghi buoi chieu 
              // firstday start
              if ($i==1 && ( ($request->get('termFrom') <> self::TERM_ALL 
                && is_null($request->get('dateDOTo') ) ) 
                || ( $request->get('termFrom') == self::TERM_AFT && !is_null($request->get('dateDOTo'))  ) )   ) {
                // Xet neu la CP:
              if ($request->get('doType') == self::OT_CR_CP ) {
                for ($j=0; $j <sizeof($dateOTlist) ; $j++) { 
                if ($dateOTlist[$j]->datenum >=0.5) {
                  if ( $request->get('termFrom') == self::TERM_MOR) {
                    self::  insertTKdayoffB(3,$doID, $date,$dateOTlist[$j]->id,0); 
              
                  } elseif ($request->get('termFrom') == self::TERM_AFT) {
                     self::  insertTKdayoffB(3,$doID, $date,0,$dateOTlist[$j]->id); 
                  }
                 $dateOTlist[$j]->datenum-=0.5;  
				 $j=	sizeof($dateOTlist);
                     }
                     }
                // Xet neu la Annual   
                 }
                 if ($request->get('doType') == self::OT_CR_ANNUAL ) {
                  if (   $auUser->annualLeaveDate >=0.5 ) {
                       if ( $request->get('termFrom') == self::TERM_MOR) {
                     self:: insertTKdayoffB($auUser->annualLeaveDate,$doID, $date,-1,0);  
                      $auUser->annualLeaveDate -=0.5;           
              
                  } elseif ($request->get('termFrom') == self::TERM_AFT) {
                      self:: insertTKdayoffB($auUser->annualLeaveDate,$doID, $date,0,-1);  
                       $auUser->annualLeaveDate -=0.5;      
                  }
   
                  }
                 }  
                 if ($request->get('doType') == self::OT_CR_UNP ) {
                  if ( $request->get('termFrom') == self::TERM_MOR) {
                     self:: insertTKdayoffB($auUser->annualLeaveDate,$doID, $date,-5,0);  
                      $auUser->annualLeaveDate -=0.5;         
              
                  } elseif ($request->get('termFrom') == self::TERM_AFT) {
                      self:: insertTKdayoffB($auUser->annualLeaveDate,$doID, $date,0,-5);  
                       $auUser->annualLeaveDate -=0.5;      
                  }
     
           
                 } 
				 // tang bien $i len de thoat khoi vong lap
				 
              }   
              // firstday ent

               elseif ($i== sizeof($dayOffLSTs) && $i>1 && $request->get('termTo')== self::TERM_MOR) {  
              // check lastday start
                  // Xet neu la CP:
              if ($request->get('doType') == self::OT_CR_CP ) {
				 
                for ($j=0; $j <sizeof($dateOTlist) ; $j++) { 
                if ($dateOTlist[$j]->datenum >=0.5) {
                self::  insertTKdayoffB(3,$doID, $date,$dateOTlist[$j]->id,0); 
                $dateOTlist[$j]->datenum-=0.5;
				$j=	sizeof($dateOTlist);
                     }
                     }
                // Xet neu la Annual   
                 }
                 if ($request->get('doType') == self::OT_CR_ANNUAL ) {
                  if (   $auUser->annualLeaveDate >=0.5 ) {
    self:: insertTKdayoffB($auUser->annualLeaveDate,$doID, $date,-1,0); 
    $auUser->annualLeaveDate -=0.5;      
                  }
                 }  
                 if ($request->get('doType') == self::OT_CR_UNP ) {
    self:: insertTKdayoffB($auUser->annualLeaveDate,$doID, $date,-5,0); 
     $auUser->annualLeaveDate -=0.5;      
                 } 
              // check lastday end  
              
              
            } else
            {   		
       
            
                // Neu nghi full ca ngay
                // Xet neu la CP:
              if ($request->get('doType') == self::OT_CR_CP ) {
                for ($j=0; $j <sizeof($dateOTlist) ; $j++) { 

            if ($dateOTlist[$j]->datenum>=1) {

            // trường hợp ngày nghỉ bù datenum nhiều hơn 1 ngày thì vào if
             self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$date,$dateOTlist[$j]->id,$dateOTlist[$j]->id);     
               $dateOTlist[$j]->datenum-=1;     
            $j=sizeof($dateOTlist);
           
           } elseif ($dateOTlist[$j]->datenum >=0.5) {
            ///  Vì chỉ còn nửa ngày phép nên xét tiếp tới những ngày nghỉ bù khác
             
            for ($k=$j+1; $k <sizeof($dateOTlist) ; $k++) { 
              if ($dateOTlist[$k]->datenum>=0.5) {
         self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$date,$dateOTlist[$j]->id,$dateOTlist[$k]->id); 
            $dateOTlist[$j]->datenum-=0.5;  
             $dateOTlist[$k]->datenum-=0.5;         
            $j=sizeof($dateOTlist);
             $k=sizeof($dateOTlist);
              
             
              }
            }
         

       
                     }
                // end Neu La CP  Xet neu la Annual   
                 }
  

            }
           
               if ($request->get('doType') == self::OT_CR_ANNUAL ) {
                  if (   $auUser->annualLeaveDate >=1 ) {
    self:: insertTKdayoffB($auUser->annualLeaveDate,$doID, $date,-1,-1);   
     $auUser->annualLeaveDate -=1;    
                  }
                 }  
                 if ($request->get('doType') == self::OT_CR_UNP ) {
    self:: insertTKdayoffB($auUser->annualLeaveDate,$doID, $date,-5,-5);  
     $auUser->annualLeaveDate -=1;     
                 } 

     }
     
   }
   
       self::updateOvertime($dateOTlist);
         $auUser->save();
   }


    // new create Dayoff end
    public static function createDayOff($request )
    {
     $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
     
      $from =  Carbon::parse($request->get('dateDOFrom'));
       $now = Carbon::now();
       $auUser;
       if ($from->year != $now->year) {
          $auUserTemp = DB::table('tck_user_leave')
                   ->select('tck_user_leave.*')
                    ->where("idCode","=",  $user->id)
                  
                    ->where("year","=", $from->year)
                    ->first();

                    $auUser=Tck_user_leave::findOrFail($auUserTemp->idCode);
                   
                   }  else  {
                     $auUser=Tck_user::findOrFail($user->id);
                   }   
                  
      $to =  Carbon::parse($request->get('dateDOTo'));
      $fromTerm=$request->get('termFrom');

     // get dayofflist and only get working day
     
    // self::validateOT($request->get('dateDOFrom'),$request->get('dateDOTo'),$request );
     // get compensation leave
      $dateOTlist=tck_overtime::where('usrID','=',$auUser->code)
      ->where('status','<>',self::OT_DELETE_CP)
      ->where('status','<>',self::MAN_DENY_1)
      ->where('status','<>',self::MAN_DENY_2)
      ->whereRaw('month(date)='.$from->month.' and year(date)='.$from->year.'  and statusCP=402 and datenum >0 ')
      ->orderBy('date','ASC')
      ->get();
       $doType=self::OT_CR_ANNUAL;
       if ($request->get('annual')==1) {
         $doType=self::OT_CR_SPECIAL;
       }
        elseif(sizeof($dateOTlist) >0) { 
       $doType=self::OT_CR_CP;
       } 
        elseif(sizeof($dateOTlist) == 0 && $auUser->annualLeaveDate <=0) { 
       $doType=self::OT_CR_UNP;
       }  elseif(sizeof($dateOTlist) == 0 && $auUser->annualLeaveDate > 0) { 
       $doType=self::OT_CR_ANNUAL;
       }  

      $i=0;
       
      $doID = DB::table('tck_dayoff')->insertGetId(
        [ 'fromDate'  =>$request->get('dateDOFrom'),
          'fromTerm'  =>$request->get('termFrom'),
          'toDate'=>$request->get('dateDOTo'),
          'toTerm'=>$request->get('termTo'),
          'note'=>$request->get('note'),
          'status'=>self:: OT_CR_STATUS,
          'usrCode'=>$auUser->code,
          'type' =>$doType,
          'created_at' => Carbon::now()->toDateTimeString(),
            ]
          );  
             $dayOffLSTs=CommonTimeFunction::checkHoliday($request->get('dateDOFrom'),$request->get('dateDOTo'));
          if ($request->get('annual')==1) {
            // Trường hợp là nghỉ đặc biệt, sau khi tạo dayoff thì trả về redirect,ko trừ ngày phép nữa.
          self:: specialDayoff ($dayOffLSTs,$doID,$request);
          return  redirect('application');
          }

          // trường hợp chỉ nghỉ trong 1 ngày thì xét
          if (is_null($request->get('dateDOTo'))) {
            // trường hợp nghỉ nguyên ngày  
            $onedaycpmain=0;

            if ( $fromTerm == '503') {

             for ($j=0; $j <sizeof($dateOTlist) ; $j++) { 
           if ($dateOTlist[$j]->datenum >=1) {
             self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$request->get('dateDOFrom'),$dateOTlist[$j]->id,$dateOTlist[$j]->id);     
               $dateOTlist[$j]->datenum-=1;     
            $j=sizeof($dateOTlist);
            $onedaycpmain=1;
           } elseif ($dateOTlist[$j]->datenum >=0.5) {
             $dateOTlist[$j]->datenum-=0.5; 
        // self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$request->get('dateDOFrom'),$dateOTlist[$j]->id,0); 
             $onedaycpmain=0.5;
            for ($k=$j+1; $k <sizeof($dateOTlist) ; $k++) { 
              if ($dateOTlist[$k]->datenum>=0.5) {
                $onedaycpmain=1;
         self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$request->get('dateDOFrom'),$dateOTlist[$j]->id,$dateOTlist[$k]->id); 
            
             $dateOTlist[$k]->datenum-=0.5;         
            $j=sizeof($dateOTlist);
             $k=sizeof($dateOTlist);
              $onedaycpmain=1;
              }
            }// nếu $onedaycpmain=0.5 tức là chỉ còn nửa ngày phép bù sẽ là nghỉ sáng bù và nghỉ chiều vào ngày phép năm annual
            if ($onedaycpmain==0.5) {
               self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$request->get('dateDOFrom'),$dateOTlist[$j]->id,-1); 
               $auUser->annualLeaveDate -=0.5; 
            }
           }
          }   
          }  // end nghỉ cả ngày 503. trường hợp nghỉ nửa ngày:
          else{
            
             for ($f=0; $f <sizeof($dateOTlist) ; $f++) { 
              if ($dateOTlist[$f]->datenum >=0.5) {
               if ( $fromTerm==501) {
                 self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$request->get('dateDOFrom'),$dateOTlist[$f]->id,0);
               }
               if ( $fromTerm==502) {
                 self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$request->get('dateDOFrom'),0,$dateOTlist[$f]->id);
               }
              
                $dateOTlist[$f]->datenum-=0.5;
                $f=sizeof($dateOTlist);
                $onedaycpmain=1;
              }
           }

          }
               if ($onedaycpmain==0 ) {
          // $onedaycpmain < 1 tức là không còn ngày nghỉ phép hoặc mới nghỉ nửa ngày, còn trả nửa ngày nữa
          if ($fromTerm=='502' ){
           self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$request->get('dateDOFrom'),0,-1);
            $auUser->annualLeaveDate -=0.5;  
          } elseif ($fromTerm=='501'  ) {
          self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$request->get('dateDOFrom'),-1,0);
            $auUser->annualLeaveDate -=0.5; 
          } else {
           self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$request->get('dateDOFrom'),-1,-1);
            $auUser->annualLeaveDate -=1;  
            }
        }
        self::updateOvertime($dateOTlist,$auUser);

         $auUser->save();

          return \App::call('App\Http\Controllers\ApplicationController@index');
          }  // end nghỉ trong 1 ngày


        
       foreach ($dayOffLSTs as $dayOffLST => $value) {    
        $date=$value->date;
        $cpmain=0;
        // nếu là ngày đầu tiên nghỉ thì chạy vào vòng if
      if ($i==0 && $request->get('termFrom')=='502' && $value->typeName=='WO') {
           for ($f=0; $f <sizeof($dateOTlist) ; $f++) { 
              if ($dateOTlist[$f]->datenum >=0.5) {
               self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$date,0,$dateOTlist[$f]->id);
                $dateOTlist[$f]->datenum-=0.5;
                $f=sizeof($dateOTlist);
                $cpmain=1;
              }
           }
        }
       // End ngày nghỉ đầu tiên
         elseif ( ( $value->typeName=='WO' ) && (  ($i <sizeof($dayOffLSTs)-1 ) || ($i==sizeof($dayOffLSTs)-1 && $request->get('termTo') <> '501' )) ) 
        { // nếu là những ngày tiếp theoo ở giữa ngày đầu và ngày cuối thì vào vòng if
          for ($j=0; $j <sizeof($dateOTlist) ; $j++) { 
           if ($dateOTlist[$j]->datenum>=1) {
            // trường hợp ngày nghỉ bù datenum nhiều hơn 1 ngày thì vào if
             self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$date,$dateOTlist[$j]->id,$dateOTlist[$j]->id);     
               $dateOTlist[$j]->datenum-=1;     
            $j=sizeof($dateOTlist);
            $cpmain=1;
           } elseif ($dateOTlist[$j]->datenum >=0.5) {
            ///  Vì chỉ còn nửa ngày phép nên xét tiếp tới những ngày nghỉ bù khác
            $other_CPday=0; 
            // nếu có ngày nghỉ bù khác thì tăng  $other_CPday lên
            for ($k=$j+1; $k <sizeof($dateOTlist) ; $k++) { 
              if ($dateOTlist[$k]->datenum>=0.5) {
         self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$date,$dateOTlist[$j]->id,$dateOTlist[$k]->id); 
            $dateOTlist[$j]->datenum-=0.5;  
             $dateOTlist[$k]->datenum-=0.5;         
            $j=sizeof($dateOTlist);
             $k=sizeof($dateOTlist);
             $cpmain=1;
              $other_CPday++;
              }
            }
             // nếu không còn ngày nghỉ bù nào khác, thì trừ nửa ngày còn lại
            if ($other_CPday == 0 ) {
        self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$date,$dateOTlist[$j]->id, -1); 
            $dateOTlist[$j]->datenum-=0.5;  
             $k=sizeof($dateOTlist);
              $auUser->annualLeaveDate -=0.5; 
             $cpmain=1;
              $other_CPday++;
            }
           }
          }
        }  
         // nếu ngày vòng cuối cùng thì tính theo vòng elseif
        elseif  ($i==sizeof($dayOffLSTs)-1 && $request->get('termTo')=='501'  && $value->typeName=='WO') {
            for ($e=0; $e <sizeof($dateOTlist) ; $e++) { 
              if ($dateOTlist[$e]->datenum >=0.5) {
               self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$date,$dateOTlist[$e]->id,0);
                $dateOTlist[$e]->datenum-=0.5;
                $e=sizeof($dateOTlist);
                $cpmain=1;
              }
           }
        }  
       
        if ($cpmain==0 ) {
          // $cpmain=0 tức là không còn ngày nghỉ phép
          if ($i==0 && $request->get('termFrom')=='502' && $value->typeName=='WO'){
           self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$date,0,-1);
            $auUser->annualLeaveDate -=0.5;  
          } elseif ($i==sizeof($dayOffLSTs)-1 && $request->get('termTo')=='501'  && $value->typeName=='WO') {
          self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$date,-1,0);
            $auUser->annualLeaveDate -=0.5; 
          } elseif ($value->typeName=='WO') {
           self::  insertTKdayoffB($auUser->annualLeaveDate,$doID,$date,-1,-1);
            $auUser->annualLeaveDate -=1;  
            }
        }
        $i++;
      }
     
     self::updateOvertime($dateOTlist,$auUser);
      $auUser->save();
       return \App::call('App\Http\Controllers\ApplicationController@index');
      }

       //    public static function validateOT($from,$to,Request $request)
      
       // }insertTKdayoffB : nếu annualLeaveDate <0 tức ko còn ngày nghỉ phép năm
      // thì là nghỉ unpaid anid=-5,pmid=-5 nếu còn 0.5 ngày phép và nghỉ cả ngày thì
      // pmid là nghỉ unpaid =-5
         public static function  insertTKdayoffB($annualLeaveDate,$doID,$date,$amID,$pmID)
       {
         if ($annualLeaveDate<=0) {
          if ($amID<0) {
            $amID=-5;
          }
            if ($pmID<0) {
            $pmID=-5;
          }

               
              } elseif ($annualLeaveDate==0.5) {
                 if ($pmID<0 && $amID<0) {
            $pmID=-5;
          }
              } 
          $tck_dayoffb=Tck_dayoffb::updateOrCreate(
                          [
              'doID' => $doID,
              'date' => $date,
              'type' => 'CP',
              'status' => '101',
              'amID' => $amID,
              'pmID' => $pmID,
                          ]);
            $tck_dayoffb->save(); 
       }
       public static function updateOvertime($dateOTlist){
        for ($f=0; $f <sizeof($dateOTlist) ; $f++) { 
          $hoUDP=tck_overtime::findOrFail($dateOTlist[$f]->id);
          $hoUDP->datenum=$dateOTlist[$f]->datenum;
          $hoUDP->save();
        }
       // $tck_user=tck_user::findOrFail($auUser->id);
       }
       public static function deleteDayoff($id,$type)
    {   
      try {
          $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
           $auUser=$user;
            $now = Carbon::now();
         if ( $type =="401" || $type=="402"|| $type=="405") {
          
           $deleteObjOVT = tck_overtime::find($id);

           if ( $deleteObjOVT ->usrID==$auUser->code) {

              $deleteObjOVT->status=self:: OT_DELETE_CP ;
              $deleteObjOVT->save();
           }
          
          } else {
       $deleteObj = tck_dayoff::find($id);
             $udpUser;
           

                                 $auUserTemp = DB::table('tck_user')
                   ->select('tck_user.*')
                    ->where("code","=",  $user->code)
                 
                    ->where("year","=",  date('Y', strtotime($deleteObj->fromDate)))
                    ->first();  
                    $udpUser=Tck_user::findOrFail($auUserTemp->id); 

       if (!is_null($deleteObj)) {
        // Có 2 trường hợp xóa : 
        //  1 xóa khi Dayoff đã được man1 và man2 approve thì trả về ngày Annual đã dùng
        //  2 xóa khi Dayoff status đang ở trạng thái create. Cũng trả về như trường hợp 1 
        // nếu Dayoff đã bị reject thì khi reject đã trả về đủ ngày Annual và CP day nê không xét
        // cho trường hớp này nữa.
         if ( ( $deleteObj->status == self:: OT_CR_STATUS || $deleteObj->status == self:: MAN_APP_1
              || $deleteObj->status == self:: MAN_APP_2)
          && 
         ( $deleteObj->type==self:: OT_CR_CP||  $deleteObj->type==self:: OT_CR_ANNUAL || $deleteObj->type==self:: OT_CR_UNP )) {
           $deleteObjB = tck_dayoffb::where('doID','=',$id)->get();

           foreach ($deleteObjB as $key => $value) {
            $del_tck_overtimeAM=tck_overtime::find($value->amID);
            
            if (!is_null($del_tck_overtimeAM)) {
              $del_tck_overtimeAM->datenum+=0.5;
              $del_tck_overtimeAM->save();
            }
            $del_tck_overtimePM=tck_overtime::find($value->pmID);
             if (!is_null($del_tck_overtimePM)) {
              $del_tck_overtimePM->datenum+=0.5;
              $del_tck_overtimePM->save();
            }
             if ($value->amID ==-1 || $value->amID ==-5) {
                  $udpUser->annualLeaveDate+=0.5;
            }
            if ($value->pmID ==-1 || $value->pmID ==-5) {
                  $udpUser->annualLeaveDate+=0.5;
            }
           
            $udpUser->save();
            $value->status=self:: OT_DELETE_CP;
           $value->save();
           }
             }
       }
      $deleteObj->status=self:: OT_DELETE_CP;$deleteObj->save();
        
       }
      
    //   return \App::call('App\Http\Controllers\OvertimeController@createDayOff');
      } catch (Exception $e) {
      }
    }
    //
    public static function createOT(Request $request)
    {
      //OT_CR_STATUS
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         // self::validateOT($request->get('fromOT'),$request->get('toOT'),$request);
          $auUser=User::findOrFail($user->id);
           $status=self::OT_CR_STATUS;
           $inStatus=$request->get('status');
           if ($inStatus!=null && $inStatus != 101 ) {
            //dd(self::OT_EDIT);
            $status=self::OT_EDIT;
          }

          $overtime= new Tck_overtime
     ([
         'usrID'=> $auUser->code,
         'date' => $request->get('dateOT'),
         'start'=>$request->get('fromOT'),
         'end' => $request->get('toOT'),
         'companyCode'=>$auUser->companyCode,
        // 'term'=>self::OT_CR_CODE,
         'mannote1' => $request->get('mannote1'),
         'mannote2' => $request->get('mannote2'),
         'note' => $request->get('note'),
         'status'=>$status,
         'statusCP'=>self::OT_CR_CODE,
                ]);
     
        $overtime->save();
          
    }
   
    public static function createLeave(Request $request)
    {
       $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         // self::validateOT($request->get('fromOT'),$request->get('toOT'),$request);
          $auUser=Tck_user::findOrFail($user->id);
          $overtime= new Tck_overtime
     ([
         'usrID'=> $auUser->code,
         'date' => $request->get('dateOT'),
         'start'=>$request->get('fromOT'),
         'end' => $request->get('toOT'),
         'companyCode'=>$auUser->companyCode,
        // 'term'=>self::OT_CR_CODE,
         'note' => $request->get('note'),
         'status'=>self::OT_CR_STATUS,
         'statusCP'=>self::LV_CR_CODE,
                ]);
        $overtime->save();


    }
    
      public static function createHO(Request $request)
    {
      //OT_CR_STATUS
      $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         // self::validateOT($request);
          $auUser=User::findOrFail($user->id);
          $dayYear= DB::table('tck_dayyear as dy')
                    ->leftjoin('tck_rankot', 'tck_rankot.typeName','=','dy.typeName')
                    ->where('dy.date','=',$request->get('dateHO'))
                    ->selectRaw('dy.date,tck_rankot.val')
                    ->first();

          $days=0;
          if($request->get('term')==503){
            $days=$dayYear->val;
          } else $days=$dayYear->val/2;

          $overtime= new Tck_overtime
     ([
         'usrID'=> $auUser->code,
         'date' => $request->get('dateHO'),
         'term'=>$request->get('term'),
         'companyCode'=>$auUser->companyCode,
         'note' => $request->get('note'),
         'status'=>self::OT_CR_STATUS,
          'statusCP'=>self::OT_CP_STATUS,
         'datenum'=>$days,
         'oriNum'=>$days,
        
                ]);
        $overtime->save();
         
    }

    /// Deny Dayoff

      public static function denyDayoff($id,$note)
    {   
    
      try {
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         // self::validateOT($request);
          $auUser=Tck_user::findOrFail($user->id);
          
       $deleteObj = tck_dayoff::find($id);
       // GET User update annualdate by code
        $udpUser=Tck_user::where('code','=',$deleteObj->usrCode)->first();
        $checkDOs = DB::table('tck_dayoff as off')
       ->join('tck_user as us','us.code','=','off.usrCode')
       ->where('off.id','=',$id)
       ->select('us.ma as maid','us.mb as mbid')
       ->first();


       if (!is_null($deleteObj)) {
       //  if ( $deleteObj->status == self:: OT_CR_STATUS ) {
           $deleteObjB = tck_dayoffb::where('doID','=',$id)->get();


           foreach ($deleteObjB as $key => $value) {
            $del_tck_overtimeAM=tck_overtime::find($value->amID);
            
            if (!is_null($del_tck_overtimeAM)) {
              $del_tck_overtimeAM->datenum+=0.5;
              $del_tck_overtimeAM->save();
            }
            $del_tck_overtimePM=tck_overtime::find($value->pmID);
             if (!is_null($del_tck_overtimePM)) {
              $del_tck_overtimePM->datenum+=0.5;
              $del_tck_overtimePM->save();
            }
             if ($checkDOs->maid==$auUser->id) {
             $value->status=self:: MAN_DENY_1;
            } 
             if ($checkDOs->mbid==$auUser->id) {
             $value->status=self:: MAN_DENY_2;
            }
            // nếu dùng ngày phép hoặc trừ âm vào ngày nghỉ thì cộng hoàn trả vào user 

            if ($value->amID ==-1 || $value->amID ==-5) {
                
                  $udpUser->annualLeaveDate+=0.5;
            }
            if ($value->pmID ==-1 || $value->pmID ==-5) {
                  $udpUser->annualLeaveDate+=0.5;
            }

            $udpUser->save();
            
           $value->save();
           }
                 if ($checkDOs->maid==$auUser->id) {
         
         $deleteObj->status=self:: MAN_DENY_1;
         $deleteObj->mannote1=$note;
         $deleteObj->save();

       }
        if ($checkDOs->mbid==$auUser->id) {
         
          $deleteObj->status=self:: MAN_DENY_2;
           $deleteObj->mannote2=$note; 
          $deleteObj->save();
       }

              //end if status = 101 create
           //  }
       }
    
        
       
      
    //   return \App::call('App\Http\Controllers\OvertimeController@createDayOff');
      } catch (Exception $e) {
      }
    }


  public static function fcn_UdpAnualLeave()
  {
    $tbl_Tck_user= Tck_user::All();
    $now = Carbon::now();
    // Case tháng 1: auto chuyển mannual Leave thành 12 với member làm đủ 1 năm và 0 day cho những member chưa đủ 1 năm
    if ($now->month==1) {
      foreach ($tbl_Tck_user as $key => $tck_user) {
         $updDate=Carbon::parse($tck_user->updDate);
         if ( $updDate->year < $now->year ) {
           $joinDate=Carbon::parse($tck_user->joinDate);
        if ($now->diffInMonths($joinDate)<14) {
          $tck_user->annualLeaveDate=0;
        } else {  $tck_user->annualLeaveDate =12;}
         $tck_user->updDate=$now;
        $tck_user->save();
         }
       
      }
     // end update tháng 1
    // Case update annualLeaveDate trường hợp tự động update   
    }
    else {
          foreach ($tbl_Tck_user as $key => $tck_user) {
            $joinDate=Carbon::parse($tck_user->joinDate);
            $updDate=Carbon::parse($tck_user->updDate);
        if ( $now->diffInMonths($joinDate)>=2 &&  $now->diffInMonths($joinDate)<14 
          && $now->month - $updDate->month >0) {
          $tck_user->annualLeaveDate += $now->month - $updDate->month ;
          $tck_user->updDate=$now;
        } 
        $tck_user->save();
      }

    }
    
  }
  public static function fcn_CountDate($fromDate,$fromTerm,$toDate,$toTerm)
  {
    $date=0;
    if (!isset($toDate)) {
      if ($fromTerm==self::TERM_AFT||$fromTerm==self::TERM_MOR) {
       $date=0.5;
      } else $date=1;
    } else{


  // Count ra số ngày giữa 2 ngày đầu và cuối,
  // nếu from term và toterm không phải là full cả ngày thì nhận giá trị 0.5 full thì nhận giá trị 1
  $date=Tck_dayyear::where('typeName','=','WO')
                    ->whereBetween('date', [$fromDate, $toDate])->get()->count(); 
                  //  dd( $fromDate.'  '.$toDate);     
                   $date -=2;


  if ($fromTerm ==self::TERM_AFT ) {
                     $date +=0.5;
                    }  else { $date +=1;}     
    if ($toTerm ==self::TERM_MOR ) {
                     $date +=0.5;
                    }  else { $date +=1;}  
                        }  

                    return $date;
  }
  // FUNCTION INSERT VÀO DAYOFFB TRONG TRƯỜNG HỢP SPECIAL DAYOFF
        public  static  function specialDayoff ($dayOffLSTs,$doID,$request)
    {
          $i=0;
     foreach ($dayOffLSTs as $dayOffLST => $value) {    
        $date=$value->date;
        
        // nếu là ngày đầu tiên nghỉ thì chạy vào vòng if
      if ($i==0 && $request->get('termFrom')=='502' && $value->typeName=='WO') {
           self::  insertTKdayoffB(100,$doID,$date,0,-10);
          
        }
       // End ngày nghỉ đầu tiên
         elseif ( ( $value->typeName=='WO' ) && (  ($i <sizeof($dayOffLSTs)-1 ) || ($i==sizeof($dayOffLSTs)-1 && $request->get('termTo') <> '501' )) ) 
        { 
           self::  insertTKdayoffB(100,$doID,$date,-10,-10);
          
        }  
         // nếu ngày vòng cuối cùng thì tính theo vòng elseif
        elseif  ($i==sizeof($dayOffLSTs)-1 && $request->get('termTo')=='501'  && $value->typeName=='WO') {
            self::  insertTKdayoffB(100,$doID,$date,-10,0);
        }  
       
        $i++;
      }
 

    }
     public static function fcn_synAnnual()
    {
      $tbl_Tck_user= Tck_user::get();
      $tbl_Tck_annual=Tck_annualleave::get();
      foreach ($tbl_Tck_annual as $key => $value) {
       foreach ($tbl_Tck_user as $key => $users) {
         if ($value->code ==$users ->code && $value->year ==$users ->year) {
                  $value->annualLeaveDate=$users->annualLeaveDate;
                   $value->save();
                   }
       }

      }
    }
    // public static function validateDayoff($code,$from,$to,$fromTerm,$toTerm)
    // {
    //   if (is_null($to)) {
    //     $to=$from;
    //     $toTerm=$fromTerm;
    //   }
    //   $dayoffbLst== DB::table('tck_dayoffb')->join('tck_dayoff','tck_dayoffb.doID','=','tck_dayoff.id') 
    //   ->where('tck_dayoff.status','<>',self::OT_DELETE_CP)
    //   ->where('tck_dayoff.status','<>',self::MAN_DENY_1)
    //   ->where('tck_dayoff.status','<>',self::MAN_DENY_2)
    //   ->whereBetween ('tck_dayoffb.date',[$from,$to]) 
    //   ->select('tck_dayoffb.amID','tck_dayoffb.pmID','tck_dayoffb.date')
    //   ->get();
    //   foreach ($dayoffbLst as $key => $dayoffb) {
    //     if ( ( $dayoffb->date > $from && $dayoffb->date <$to )
    //     || (  $dayoffb->date == $from  && !( $fromTerm==502 &&  $dayoffb->pmID ==0 ) )
    //     || (  $dayoffb->date == $toDate  && !( $toTerm==501 &&  $dayoffb->amID ==0 ))
    //       ) {
    //       return false;
    //     }
    //   }
    //   return true;
    // }

}
