<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_cat extends Model
{
    //
   // use SoftDeletes;
   // thằng này hiện tại nó chưa có soft delete
   // sẽ có lúc gặp trường hợp mà be có soft delete con front end thi khong
    
    protected $fillable = [
        'name','slug','parent_id','user_id'
    ];
    function product_cat_children(){
        return $this->hasMany(Product_cat::class,'parent_id');
    }
 
    function products(){
        return $this->hasMany('App/Product');
    }
    
}
