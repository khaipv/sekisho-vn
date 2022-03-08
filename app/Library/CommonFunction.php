<?php

namespace App\Library;


use DateTime;
use Lang;
use Carbon\Carbon;
use DB;

class CommonFunction
{
    const STATUS_PASSWORD_NOT_CORRECT = 401;
    const STATUS_VALIDATE_ERROR = 422;
    const STATUS_FINISH_WORKED = 409;
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
	 public static function generateColumnExportCsvCandi()
    {
        return array(
             'code'  =>  'code' ,
            'Name'  =>  'Name' ,
            'VName'  =>  'VName' ,
                'birth'  =>  'birth' ,
                'email' =>  'email' ,
                'mobile'    =>  'mobile'    ,
                'sex'   =>  'sex'   ,
                'married'   =>  'married'   ,
                'University'    =>  'University'    ,
                'major' =>  'major' ,
                'graduates' =>  'graduates' ,
                'Birthplace'    =>  'Birthplace'    ,
                'curAdd'    =>  'curAdd'    ,
                'Japan' =>  'Japan' ,
                'English'   =>  'English'   ,
                'WorkPlace' =>  'WorkPlace' ,
                'sources'   =>  'sources'   ,
                'preview'   =>  'preview'   ,
                'previewDate'   =>  'previewDate'   ,
                'entering'  =>  'entering'  ,
                'enteringDate'  =>  'enteringDate'  ,
                'situation' =>  'situation' ,
                'plan'  =>  'plan'  ,
                'col100name'    =>  'col100name'    ,
				'staffname' =>   'staffname',
				 'Comfirm Mail'     =>       'mailstatus',
				 'Ready Change Job'  =>      'changeJob',
				 'Ready Time'        =>       'readyTime',
                 'col1'        =>       'col1'  ,
                 'col39'        =>       'col39'  ,
   
         
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
	 public  static function generateDataExportCsvCandi($columns, $arrDatas)
    {
        $row_order = 0;
        $data = [];
		
        if ($arrDatas) {
            foreach ($arrDatas as $key => $arrData) {

               $row_order++;
               $row = [];
                  $row[$columns['code']]         = $arrData->code;
                $row[$columns['Name']]         = $arrData->Name;
                $row[$columns['VName']]         = $arrData->VName;
                $row[$columns['birth']]         = $arrData->birth;
                $row[$columns['email']]         = $arrData->email;
                $row[$columns['mobile']]         = $arrData->mobile;
                $row[$columns['sex']]         = $arrData->sex;
                $row[$columns['married']]         = $arrData->married;
                $row[$columns['University']]         = $arrData->University;
                $row[$columns['major']]         = $arrData->major;
                $row[$columns['graduates']]         = $arrData->graduates;
                $row[$columns['Birthplace']]         = $arrData->Birthplace;
                $row[$columns['curAdd']]         = $arrData->curAdd;
                $row[$columns['Japan']]         = $arrData->Japan;
                $row[$columns['English']]         = $arrData->English;
                $row[$columns['WorkPlace']]         = $arrData->WorkPlace;
                $row[$columns['sources']]         = $arrData->sources;
                $row[$columns['preview']]         = $arrData->preview;
                $row[$columns['previewDate']]         = $arrData->previewDate;
                $row[$columns['entering']]         = $arrData->entering;
                $row[$columns['enteringDate']]         = $arrData->enteringDate;
                $row[$columns['situation']]         = $arrData->situation;
                $row[$columns['plan']]         = $arrData->plan;
                $row[$columns['col100name']]         = $arrData->col100name;
				$row[$columns['staffname']]         = $arrData->staffname;
				$row[$columns['Comfirm Mail']]         = $arrData->mailstatus;
				$row[$columns['Ready Change Job']]         = $arrData->changeJob;
				$row[$columns['Ready Time']]         = $arrData->readyTime;
                $row[$columns['col1']]         = $arrData->col1;
                $row[$columns['col39']]         = $arrData->col39;
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
     public  static function genDataExCandiSearch($columns, $arrDatas)
    {
        $row_order = 0;
        $data = [];
        
        if ($arrDatas) {
            foreach ($arrDatas as $key => $arrData) {

               $row_order++;
               $row = [];
                  $row[$columns['code']]         = $arrData->code;
                $row[$columns['name']]         = $arrData->name;
                $row[$columns['email']]         = $arrData->email;
                $row[$columns['mobile']]         = $arrData->mobile;
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
	  public static function generateColumnExportCsvDiv()
    {
        return array(
    'Code'                         => 'Code',
    'divisionname'                       => 'divisionname' ,
    'companyname'                        => 'companyname' ,
    'Tel'                      =>  'Tel'  ,
    'Importance'                     =>   'Importance'   ,
    'advertise'                   =>  'advertise' ,
    'introduce'               => 'introduce'       ,
    'name'                 =>'name',
    'created_at'                 => 'created_at',
  
   
         
        );
    }
          public static function generateColumnExportCsvOrder()
    {
        return array(
               
                   'Code'                         => 'Code',
    'divisionName'                       => 'divisionname' ,
    'companyname'                        => 'companyname' ,
    'title'                      =>  'title'  ,
    'introduceDate'                     =>   'introduceDate'   ,
    'workingDate'                   =>  'workingDate' ,
    'Type'               => 'Type'       ,
    'pic'                 =>'pic',
    'created_at'                 => 'created_at',
      'JapaneseLevel'                 => 'JapaneseLevel',
        'EnglishLevel'                 => 'EnglishLevel',
          'skill'                 => 'skill',
          'indispensable'                 => 'indispensable',
            'warantyName'                 => 'warantyName',
              'warrantyPeriod'                 => 'warrantyPeriod',
                'recruit_num'                 => 'recruit_num',
                  'age'                 => 'age',
                   'sex'                 => 'sex',
        'Salary'                 => 'Salary',
      'imcome'                 => 'imcome',
        'invoiceCK'                 => 'invoiceCK',
          'invoiceDate'                 => 'invoiceDate',
  
   
         
        );
    }

    public static function genColumnExpCsvCanSearch()
    {
        return array(
    'code'                         => 'No',
    'name'                       => 'Name' ,
    'email'                        => 'Email' ,
    'mobile'                      =>  'Mobile'  ,
  
  
   
         
        );
    }
     public  static function generateDataExportCsvDiv($columns, $arrDatas)
    {
        $row_order = 0;
        $data = [];
        if ($arrDatas) {
            foreach ($arrDatas as $key => $arrData) {

               $row_order++;
               $row = [];
               $row[$columns['Code']]         = $arrData->code  ;
               $row[$columns['divisionname']]=$arrData->divisionname                  ;
               $row[$columns['companyname']]=$arrData->companyname                    ;
               $row[$columns['Tel']]=$arrData->tel                ;
               $row[$columns['Importance']]=$arrData->Importance              ;
               $row[$columns['advertise']]=$arrData->advertise             ;
                $row[$columns['introduce']]=$arrData->introduce             ;
               $row[$columns['name']]=$arrData->name    ;
               $row[$columns['created_at']]=$arrData->created_at      ;          
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
       public  static function generateDataExportCsvOrder($columns, $arrDatas)
    {
        $row_order = 0;
        $data = [];
        if ($arrDatas) {
            foreach ($arrDatas as $key => $arrData) {

               $row_order++;
               $row = [];
               $row[$columns['Code']]         = $arrData->code  ;
               $row[$columns['divisionName']]=$arrData->divisionName                  ;
               $row[$columns['companyname']]=$arrData->companyname                    ;
               $row[$columns['title']]=$arrData->title                ;
               $row[$columns['introduceDate']]=$arrData->introduceDate              ;
               $row[$columns['workingDate']]=$arrData->workingDate             ;
               
               $row[$columns['pic']]=$arrData->pic    ;
               $row[$columns['created_at']]=$arrData->created_at      ;     
               $row[$columns['JapaneseLevel']]=$arrData->JapaneseLevel      ; 
               $row[$columns['EnglishLevel']]=$arrData->EnglishLevel      ; 
               $row[$columns['skill']]=$arrData->skill      ; 
                 $row[$columns['indispensable']]=$arrData->indispensable      ; 
               $row[$columns['warantyName']]=$arrData->warantyName      ;   
               $row[$columns['warrantyPeriod']]=$arrData->warrantyPeriod      ; 
               $row[$columns['recruit_num']]=$arrData->recruit_num      ; 
               $row[$columns['age']]=$arrData->age      ;   
                $row[$columns['sex']]=$arrData->sex      ;
               $row[$columns['Salary']]=$arrData->Salary      ;   
               $row[$columns['imcome']]=$arrData->imcome      ; 
               $row[$columns['invoiceCK']]=$arrData->invoiceCK      ; 
               $row[$columns['invoiceDate']]=$arrData->invoiceDate      ;    
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
     public  static function getSekiUser($status)
    {
       
        $users=DB::table('users')->where('status','=',$status)->get();
        return $users;
    }

}
