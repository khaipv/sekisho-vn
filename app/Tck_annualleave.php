<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Tck_annualleave extends Model
{
    //
    use Sortable;
    protected $table='tck_annualleave';
 	protected $fillable = [ 'udpCode','code','annualLeaveDate','year'];
    public $sortable = ['udpCode','code','annualLeaveDate','year'];

}
