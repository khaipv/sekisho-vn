<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Dr_kind extends Model
{
    //
    use Sortable;
    protected $table='dr_kind';

    protected $fillable = [ 'id','code','name'];
    public $sortable = [ 'id','code','name'];
}
