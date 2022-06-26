<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    //
    protected $table = "district";
    protected $fillable = [
        'name', 'prefix', 'province_id',
    ];

    function province()
    {
        return $this->belongsTo('App/Province', 'province_id');
    }

    function ward()
    {
        return $this->hasMany('App\Ward', 'district_id', 'id');
    }
    function street()
    {
        return $this->hasMany('App\Street', 'district_id', 'id');
    }
}
