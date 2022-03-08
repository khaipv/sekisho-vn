<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    //
    use Sortable;
    protected $table='master';
 	protected $fillable = ['id','code','name','type','sort','typeName','crUser','udpUser','val','created_at','updated_at'];
    public $sortable = ['id','code','name','type','sort','typeName','crUser','udpUser','val','created_at','updated_at'];

}
