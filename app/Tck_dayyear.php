<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Tck_dayyear extends Model
{
    //
    use Sortable;
    protected $table='tck_dayyear';
 	protected $fillable = ['id','date','name','note','type','typeName'];
    public $sortable = ['id','date','name','note','type','typeName'];

}
