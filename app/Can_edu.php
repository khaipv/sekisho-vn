<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Can_edu extends Model
{
    //
    use Sortable;
    protected $table='can_edu';

    protected $fillable = ['id','idcan','education','date'];
    public $sortable = ['id','idcan','education','date'];
}
