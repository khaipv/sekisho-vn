<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    use Sortable;
    protected $table='client';

    protected $fillable = ['code','companyname','url','status'];
    public $sortable = ['code','companyname','url','status'];
}
