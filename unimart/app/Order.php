<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'code_bill', 'customer_id', 'total', 'subtotal', 'discount', 'payment', 'status', 'order_coupon_id', 'phone_number', 'address', 'email', 'name', 'date_order'
    ];
    function customer()
    {
        return $this->belongsTo('App\Customer');
    }
    function orders_detail()
    {
        return $this->hasMany('App\Order_detail');
    }
    function order_coupon()
    {
        return $this->belongsto('App\Order_coupon');
    }
    function fee_shipping()
    {
        return $this->belongsTo('App\Fee_shipping', 'fee_shipping_id');
    }
}
