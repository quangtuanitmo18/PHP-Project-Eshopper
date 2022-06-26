<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    //
    protected $table = "province";
    protected $fillable = [
        'id', 'name', 'code'
    ];

    function district()
    {
        return $this->hasMany('App\District', 'province_id', 'id');
    }
    function ward()
    {
        return $this->hasMany('App\Ward', 'province_id', 'id');
    }
    function street()
    {
        return $this->hasMany('App\Street', 'province_id', 'id');
    }
}
