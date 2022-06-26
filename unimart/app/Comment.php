<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'comment', 'product_id', 'customer_id', 'deleted_at', 'created_at', 'updated_at', 'parent_id', 'status', 'user_id'
    ];
    function product()
    {
        return $this->belongsTo('App\Product');
    }
    function customer()
    {
        return $this->belongsTo('App\Customer');
    }
    // function reply_comment()
    // {
    //     return $this->belongsTo('App\Comment', 'parent_id');
    // }
    function user()
    {
        return $this->belongsTo('App\User');
    }
}
