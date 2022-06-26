<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_tag extends Model
{
    //
    protected $table="product_tag";
    protected $fillable = [
        'product_id','tag_id',
    ];
}
