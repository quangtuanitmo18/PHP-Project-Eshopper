<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    //
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name', 'status', 'description', 'content', 'price', 'price_cost', 'price_sale', 'user_id', 'product_cat_id', 'product_brand_id', 'qty', 'thumbnail', 'browse', 'featured', 'best_seller', 'slug', 'qty_sold', 'price_cost', 'views_count'
    ];
    function user()
    {
        return $this->belongsTo('App\User');
    }
    function product_cat()
    {
        return $this->belongsTo('App\Product_cat');
    }
    function orders_detail()
    {
        return $this->hasMany('App\Order_detail');
    }
    function product_images()
    {
        return $this->hasMany('App\Product_image');
    }
    function tags()
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }
    function product_brand()
    {
        return $this->belongsTo('App\Product_brand');
    }
}
