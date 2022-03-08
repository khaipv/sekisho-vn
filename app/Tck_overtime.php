<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Tck_overtime extends Model
{
    //
    use Sortable;
    protected $table='tck_overtime';
 	protected $fillable = [ 'companyCode','code','term','usrID','date','dateCP','datenum','oriNum','statusCP','start','end','status','note','mannote1','mannote2'];
    public $sortable = [ 'companyCode','code','term','usrID','date','dateCP','datenum','oriNum','statusCP','start','end','status','note','mannote1','mannote2'];

}
