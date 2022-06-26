<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name','display_name'
    ];
    function users(){
        return $this->belongsToMany('App\User');
    }
    function permissions(){
        return $this->belongsToMany('App\Permission');
    }
   
    
}
