<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistical extends Model
{
    //
    protected $table = "statistical";
    protected $fillable = [
        'sales', 'profit', 'number_product', 'number_order', 'date_order',
    ];
}
