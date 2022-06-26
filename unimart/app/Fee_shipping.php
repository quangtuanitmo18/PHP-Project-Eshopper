<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee_shipping extends Model
{
    //
    protected $table = "fee_shipping";
    protected $fillable = [
        'id', 'province_id', 'district_id', 'ward_id', 'fee_shipping'
    ];
    function province()
    {
        return $this->belongsTo('App\Province', 'province_id');
    }
    function district()
    {
        return $this->belongsTo('App\District', 'district_id');
    }
    function ward()
    {
        return $this->belongsTo('App\Ward', 'ward_id');
    }
    function order()
    {
        return $this->hasMany('App\Order', 'fee_shipping_id', 'id');
    }
}
