<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Follow_candidate extends Model
{
    
    public $timestamps = true;
    protected $table='follow_candidate';
    use Sortable;
    protected $fillable = [ 'id','date','time_start','time_end','action','job_seeking_need','candidates','sekisho_pic','note'];

    public $sortable = [ 'id','date','time_start','time_end','action','job_seeking_need','candidates','sekisho_pic','note'];

   
}
