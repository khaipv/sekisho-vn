<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Tck_print extends Model
{
    //
       protected $fillable = [
       'id','code','companyCode','name','date','attendance','leaving','workingTime','late','early','department','wt_code','status','overtime','ovt_approve','work_time','note','mannote1','mannote2','attendance_ori','leaving_ori','inEdit','outEdit'
    ];
    protected $table='tck_print';
    use Sortable;
    
   
}
