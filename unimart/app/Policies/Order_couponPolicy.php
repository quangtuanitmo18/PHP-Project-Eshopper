<?php

namespace App\Policies;

use App\Order_coupon;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_couponPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Order_coupon  $orderCoupon
     * @return mixed
     */
    public function view(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.order-coupon-list'));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.order-coupon-add'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Order_coupon  $orderCoupon
     * @return mixed
     */
    public function update(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.order-coupon-edit'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Order_coupon  $orderCoupon
     * @return mixed
     */
    public function delete(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.order-coupon-delete'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Post_cat  $postCat
     * @return mixed
     */
    public function action(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.order-coupon-action'));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Order_coupon  $orderCoupon
     * @return mixed
     */
    public function restore(User $user, Order_coupon $orderCoupon)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Order_coupon  $orderCoupon
     * @return mixed
     */
    public function forceDelete(User $user, Order_coupon $orderCoupon)
    {
        //
    }
}
