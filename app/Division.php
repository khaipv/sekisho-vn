<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Division extends Model
{
    //
     use Sortable;
    protected $table='division';
     
    protected $fillable = [ 'companyid','code','pic','pic_s','divisionname','fullName','fullNameJp','firstName','midleName','lastName','firstNameJp','midleNameJp','lastNameJp','mobile','email','address','provinceId','national_ID','districtId','wardId','introduce','advertise','rate','condition','worktime1','worktime2','holidays','review','yearBonus','welfare','otherWelfare','status'];
    public $sortable =[ 'companyid','code','pic','pic_s','divisionname','fullName','fullNameJp','firstName','midleName','lastName','firstNameJp','midleNameJp','lastNameJp','mobile','email','address','provinceId','national_ID','districtId','wardId','introduce','advertise','rate','condition','worktime1','worktime2','holidays','review','yearBonus','welfare','otherWelfare','status'];
    public function companynameSortable($query, $direction)
    {
        return $query
                    ->orderBy('clientName', $direction)
                      ->select('division.*', 'province.Name as provinceName','district.Name as districtName','nation.commonName as nationName','client.companyname as clientName','users.name as pics','client.code as clientCode','imp.name as rate2','introduce.name as introduceName','advertise.name as advertiseName');
    }
        public function divisionSortable($query, $direction)
    {
        return $query
                    ->orderBy('divisionname', $direction)
                      ->select('division.*', 'province.Name as provinceName','district.Name as districtName','nation.commonName as nationName','client.companyname as clientName','users.name as pics','client.code as clientCode','imp.name as rate2','introduce.name as introduceName','advertise.name as advertiseName');
    }
}
