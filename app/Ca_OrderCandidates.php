<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Ca_OrderCandidates extends Model
{
    protected $table='ca_OrderCandidates';
    use Sortable;
    protected $fillable = ['id','idOrder','idCandidates','status','note','introduceDate','enterDate'];
    public $sortable =['id','idOrder','idCandidates','status','note','introduceDate','enterDate'];
      
}