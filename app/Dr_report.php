<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;


class Dr_report extends Model
{
    //
    use Sortable;
    protected $table='dr_report';

    protected $fillable = [ 'id','date','from','to','companyID','divisionID','customer','visitor','kind','title','detail','userID','way','other','userName','history'];
    public $sortable = [ 'id','date','from','to','companyID','divisionID','customer','visitor','kind','title','detail','way','other','userName'];
}
