<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post_cat extends Model
{
    //
    protected $fillable = [
        'name', 'slug', 'parent_id', 'user_id'
    ];
    function posts()
    {
        return $this->hasMany('App\Post');
    }
    function post_cat_children()
    {
        return $this->hasMany(Post_cat::class, 'parent_id');
    }
}
