<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class tck_user_leave extends Model
{
    //
    use Sortable;
    protected $table='tck_user_leave';
    protected $fillable = ['id','companyCode','name','code','annualLeaveDate','anualLeaveHour','usTime','compenTime','whID','role','ma','mb','joinDate','updDate','idcode'
    ];
 
}
