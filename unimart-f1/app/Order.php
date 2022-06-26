<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //


    protected $fillable = [
        'code_bill', 'customer_id', 'total', 'subtotal', 'discount', 'payment', 'status', 'order_coupon_id', 'final_total', 'phone_number', 'address', 'email', 'name', 'fee_shipping_id', 'date_order'
    ];
    function customer()
    {
        return $this->belongsTo('App\Customer');
    }
    function order_coupon()
    {
        return $this->belongsTo('App\Order_coupon');
    }
    function fee_shipping()
    {
        return $this->belongsTo('App\Fee_shipping');
    }
}
