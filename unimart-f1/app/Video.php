<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    protected $table = "videos";
    protected $fillable = [
        'title', 'image_path', 'slug', 'desc', 'link', 'status', 'deleted_at', 'created_at', 'updated_at',
    ];
}
