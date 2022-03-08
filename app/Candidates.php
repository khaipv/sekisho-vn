<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Candidates extends Model
{
    //
    
    protected $table='candidates';
    use Sortable;
    protected $fillable = [ 'id','code','firstName','midleName','lastName','firstNameJ','midleNameJ','lastNameJ'
 ,'birth','mobile','email','sex','married','university','majors','graduates'
 ,'currentAdd','birthPlace','jLevel','eLevel','toeic','workPlace','workPlaceTxt','source'
 ,'situation','universityName','interview','plan','mandan','mandanDate','workcheck','otherManu' ,'otherAdmin','mobilestatus','skill','sourceTxt'
 ,'workDate','majorsTxt','otherIT','otherTech','other','contact','col1','col39','col100','exp39','exp100','staff','changeJob','readyTime','mailstatus','wpselect','foreigner','created_at','updated_at'
,'korean','chinese','engeval','jpeval','evaluation','evalDate'];

     public $sortable = [ 'id','code','firstName','midleName','lastName','firstNameJ','midleNameJ','lastNameJ'
 ,'birth','mobile','email','sex','married','university','majors','graduates'
 ,'currentAdd','birthPlace','jLevel','eLevel','toeic','workPlace','workPlaceTxt','source'
 ,'situation','universityName','interview','plan','mandan','mandanDate','workcheck','wpselect'
 ,'workDate','majorsTxt','otherIT','otherTech','other','col1','staff','changeJob','readyTime','mailstatus','foreigner','mobileStatus','skill','sourceTxt'
,'created_at','updated_at','korean','chinese','engeval','jpeval','evaluation','evalDate'];



   
}
