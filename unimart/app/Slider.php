<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'name','image_path','position','status','user_id','decs'
    ];
    function user(){
        return $this->belongsTo('App\User');
    }
}
