<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    //
    use softDeletes;
    
    protected $fillable = [
        'name','image_path','position','status','user_id','decs'
    ];
}
