<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Tck_workinghours extends Model
{
    //
    use Sortable;
    protected $table='tck_workinghours';
 
}
