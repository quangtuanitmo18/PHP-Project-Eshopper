<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
     use SoftDeletes;
     protected $fillable = [
          'title', 'slug', 'thumbnail', 'content', 'status', 'post_cat_id', 'user_id', 'post_featured', 'views_count'
     ];
     function post_cat()
     {
          return $this->belongsTo('App\Post_cat');
     }
}
