<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    function products()
    {
        return $this->hasMany('App\Product');
    }
    function product_cats()
    {
        return $this->hasMany('App\Product_cat');
    }
    function roles()
    {
        return $this->belongsToMany('App\Role');
    }
    public function check_permission_access($permission_check)
    {
        // b1 lấy các quyền của user đang đăng nhập
        // b2 so sánh giá trị đưa vào của route hiện tại và quyền của user
        $roles = auth()->user()->roles;
        foreach ($roles as $role) {
            $permissions = $role->permissions;
            if ($permissions->contains('key_permission', $permission_check)) {
                return true;
            }
        }
        return false;
    }
}
