<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product_brand extends Model
{
    //


    protected $fillable = [
        'name', 'desc', 'image', 'user_id', 'status', 'deleted_at', 'created_at', 'update_at', 'position', 'slug'
    ];
    function user()
    {
        return $this->belongsTo('App\User');
    }
    function products()
    {
        return $this->hasMany('App\Product');
    }
}
