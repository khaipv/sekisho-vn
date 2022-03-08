<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Tck_dayoff extends Model
{
    //
    use Sortable;
    protected $table='tck_dayoff';
 	protected $fillable = [ 'id','code','usrCode','fromDate','fromTerm','toDate','toTerm','status','usrApp','usrUDP','type','days','note','mannote1','history','mannote2',];
    public $sortable = [ 'id','code','usrCode','fromDate','fromTerm','toDate','toTerm','status','usrApp','usrUDP','type','days','note','history','mannote1','mannote2'];

}
