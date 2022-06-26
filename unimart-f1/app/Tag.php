<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $fillable = [
        'name',
    ];
    function products()
    {
        return $this->belongsToMany('App\Product');
    }
}
