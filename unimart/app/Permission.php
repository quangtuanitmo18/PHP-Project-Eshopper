<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    protected $fillable = [
        'name', 'display_name', 'parent_id', 'key_permission'
    ];
    function roles()
    {
        return $this->belongsToMany('App\Role');
    }
    function permissionChildrent()
    {
        return $this->hasMany('App\Permission', 'parent_id');
    }
}
