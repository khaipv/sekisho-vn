<?php

namespace App;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
	 use Sortable;
   protected $table='staff';
   protected $fillable = ['name','firstName','midleName','lastName','firstNameJ','midleNameJ','lastNameJ','birth','tell'   ,'mobile','email','company_id','division_id','department','position','status','country','province','district','ward','add','magazine','created_at','updated_at'];
   public $sortable = ['name','birth','tell','mobile','email','company_id','division_id','department','position','status','country','province','district','ward','add','magazine','created_at','updated_at'];
}