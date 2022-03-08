<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Tck_dayoffb extends Model
{
    //
    use Sortable;
    protected $table='tck_dayoffb';
 	protected $fillable = ['id','doID','otID','date','type','status','amID','pmID'];
    public $sortable = ['id','doID','otID','date','type','status','amID','pmID'];

}
