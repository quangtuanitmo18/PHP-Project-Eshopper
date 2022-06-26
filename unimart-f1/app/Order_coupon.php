<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_coupon extends Model
{
    //
    protected $table = "order_coupons";
    protected $fillable = [
        'id', 'code', 'qty', 'condition', 'value', 'status', 'data_end', 'event_id', 'coupon_used'
    ];
}
