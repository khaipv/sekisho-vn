<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Tck_rankot extends Model
{
    //
    use Sortable;
    protected $table='tck_rankot';
 	protected $fillable = [ 'id','typeName','Name','from','to','val'];
    public $sortable = ['id','typeName','Name','from','to','val'];

}
