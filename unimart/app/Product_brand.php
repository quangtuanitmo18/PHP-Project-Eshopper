<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Product_brand extends Model
{
    //
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name', 'desc', 'image', 'user_id', 'status', 'deleted_at', 'created_at', 'update_at', 'position', 'slug',
    ];
    function user()
    {
        return $this->belongsTo('App\User');
    }
}
