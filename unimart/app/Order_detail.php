<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    //
    protected $fillable = [
        'order_id', 'product_id', 'price', 'qty', 'total', 'price_cost',
    ];
    function order()
    {
        return $this->belongsTo('App\Order');
    }
    function product()
    {
        return $this->belongsTo('App\Product')->withTrashed();
    }
}
