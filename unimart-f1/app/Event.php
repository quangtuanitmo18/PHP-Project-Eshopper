<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $fillable = [
        'name','slug','status'
    ];
    function order_coupons(){
        return $this->hasMany('App\Order_coupon');
    }
}
