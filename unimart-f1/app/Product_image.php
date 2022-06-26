<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_image extends Model
{
    //
    protected $fillable = [
        'image_path','image_name','product_id',
    ];
    function product(){
        return $this->belongsTo('App\Product');
    }
}
