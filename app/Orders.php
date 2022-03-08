<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Orders extends Model
{
    protected $table='order';
    use Sortable;
    protected $fillable = ['code','divisionID','clientID','title','type','orderDate','progress','workingDate','introduceDate','workingPlace','pic_s','recruit_num','age','ageMax','sex','JLevel','ELevel','condition','skill','introduceFee','introduceFeeMax','warrantyPeriod','warranty','note','indispensable','occupation','status','user','upd','partner','partnerfee','salaryFrom','salaryTo','unitSaFrom','unitSaTo','unitFrom','unitTo','invoiceCK','invoiceDate','priority'];
    public $sortable =['code','divisionID','clientID','title','type','orderDate','progress','workingDate','introduceDate','workingPlace','pic_s','recruit_num','age','ageMax','sex','JLevel','ELevel','condition','skill','introduceFee'
    ,'introduceFeeMax','warrantyPeriod','warranty','note','indispensable','occupation','status','user','upd','partner','partnerfee','salaryFrom','salaryTo','unitSaFrom','unitSaTo'
    ,'unitFrom','unitTo','invoiceCK','invoiceDate','priority'];
        public function companynameSortable($query, $direction)
    {
        return $query
                    ->orderBy('companyname', $direction)
                      ->select('order.*', 'division.divisionname as divisionName','client.companyname','users.name as pics','status.name as statusName','pro.name as progressName','type.name as typeName','jp.name as jpName','eng.name as engName','waranty.name as warantyName','pro.name as progressName');
    }
      public function divisionNameSortable($query, $direction)
    {
        return $query
                    ->orderBy('divisionname', $direction)
                      ->select('order.*', 'division.divisionname as divisionName','client.companyname','users.name as pics','status.name as statusName','pro.name as progressName','type.name as typeName','jp.name as jpName','eng.name as engName','waranty.name as warantyName','pro.name as progressName');
    }
}