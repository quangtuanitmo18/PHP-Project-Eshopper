<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History_edit_comment extends Model
{
    //
    protected $table = "history_edit_comment";
    protected $fillable = [
        'comment_edit', 'user_id', 'comment_id', 'date_edit',
    ];
    function comment()
    {
        return $this->belongsTo('App\Comment', 'comment_id');
    }
    function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
