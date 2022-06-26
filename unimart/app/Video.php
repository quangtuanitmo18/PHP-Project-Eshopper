<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    //
    use SoftDeletes;
    protected $table = "videos";
    protected $fillable = [
        'title', 'image_path', 'slug', 'desc', 'link', 'status', 'deleted_at', 'created_at', 'updated_at',
    ];
}
