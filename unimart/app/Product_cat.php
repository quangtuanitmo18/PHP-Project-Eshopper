<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Product_cat extends Model
{
    //
    use Notifiable;
    protected $fillable = [
        'name','slug','parent_id','user_id','position'
    ];
    function user(){
        return $this->belongsTo('App/User');
    }
    function products(){
        return $this->hasMany('App/Product');
    }
    
}
