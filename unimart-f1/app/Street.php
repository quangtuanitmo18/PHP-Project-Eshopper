<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    //
    protected $table = "street";
    protected $fillable = [
        'name', 'prefix', 'province_id', 'district_id'
    ];

    function district()
    {
        return $this->belongsTo('App\District', 'district_id');
    }
    function province()
    {
        return $this->belongsTo('App\Province', 'province_id');
    }
}
