<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    //
    protected $table="social";
    protected $fillable=[
        'provider_user_id','provider','user_id'
    ];
    function customer(){
        return $this->belongsTo('App\Customer');
    }
}
