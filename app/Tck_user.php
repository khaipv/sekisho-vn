<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Tck_user extends Model
{
    //
    use Sortable;
    protected $table='tck_user';
    protected $fillable = [ 'id','companyCode','name','code','annualLeaveDate','whID','role','ma','mb','joinDate','updDate','depart','year','leaveDate'
    ];
 
}
