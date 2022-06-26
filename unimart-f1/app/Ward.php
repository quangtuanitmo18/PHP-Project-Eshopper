<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    //
    protected $table = "ward";
    protected $fillable = [
        'name', 'prefix', 'province_id', 'district_id'
    ];

    function district()
    {
        return $this->belongsTo('App\District', 'district_id');
    }
    function province()
    {
        return $this->belongsTo('App/Province', 'province_id');
    }
}
