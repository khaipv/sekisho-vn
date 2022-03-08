<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Dr_master extends Model
{
    //
    use Sortable;
    protected $table='dr_master';
 	protected $fillable = ['id','code','name','type','sort','typeName','crUser','udpUser','val','created_at','updated_at'];
    public $sortable = ['id','code','name','type','sort','typeName','crUser','udpUser','val','created_at','updated_at'];

}
