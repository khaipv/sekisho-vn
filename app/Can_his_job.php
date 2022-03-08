<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Can_his_job extends Model
{
    //
    use Sortable;
    protected $table='can_his_job';

    protected $fillable = ['id','idcan','idoc','company','position','detail','from','to'];
    public $sortable = ['id','idcan','idoc','company','position','detail','from','to'];
}
