<?php

namespace App\Policies;

use App\Product_cat;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Category_productPolicy
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
     * @param  \App\Product_cat  $productCat
     * @return mixed
     */
    public function view(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.category-product-list'));
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
        return $user->check_permission_access(config('permissions.access.category-product-add'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product_cat  $productCat
     * @return mixed
     */
    public function update(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.category-product-edit'));
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
        return $user->check_permission_access(config('permissions.access.category-product-action'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product_cat  $productCat
     * @return mixed
     */
    public function delete(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.category-product-delete'));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product_cat  $productCat
     * @return mixed
     */
    public function restore(User $user, Product_cat $productCat)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product_cat  $productCat
     * @return mixed
     */
    public function forceDelete(User $user, Product_cat $productCat)
    {
        //
    }
}
