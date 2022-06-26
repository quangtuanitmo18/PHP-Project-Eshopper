<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order_coupon extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'code', 'event_id', 'condition', 'qty', 'value', 'date_end', 'status', 'coupon_used'
    ];
    function event()
    {
        return $this->belongsTo('App\Event');
    }
}
