<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;


class Dr_report_h extends Model
{
    //
    use Sortable;
    protected $table='dr_report_h';

    protected $fillable = [ 'id','date','from','to','companyID','divisionID','customer','visitor','kind','title','detail','userID','way','other','history','faID'];
    public $sortable = [ 'id','date','from','to','companyID','divisionID','customer','visitor','kind','title','detail','way','other','history','faID'];
}
