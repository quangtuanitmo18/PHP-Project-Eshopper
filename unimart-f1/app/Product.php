<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    //
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name', 'status', 'description', 'content', 'price', 'user_id', 'product_cat_id', 'qty', 'thumbnail', 'browse', 'featured', 'best_seller', 'slug', 'qty_sold', 'price_cost', 'views_count'
    ];

    function product_images()
    {
        return $this->hasMany('App\Product_image');
    }
    function product_cat()
    {
        return $this->belongsTo('App\Product_cat');
    }
    function tags()
    {
        return $this->belongsToMany('App\Tag');
    }
    function product_brand()
    {
        return $this->belongsTo('App\Product_brand');
    }
}
