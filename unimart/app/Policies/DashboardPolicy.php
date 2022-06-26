<?php

namespace App\Policies;

use App\Dashboard;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DashboardPolicy
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
     * @param  \App\Dashboard  $dashboard
     * @return mixed
     */
    public function view(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.dashboard-list'));
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
        return $user->check_permission_access(config('permissions.access.dashboard-add'));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function print(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.dashboard-print'));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function detail(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.dashboard-detail'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Dashboard  $dashboard
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->check_permission_access(config('permissions.access.dashboard-edit'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Dashboard  $dashboard
     * @return mixed
     */
    public function delete(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.dashboard-delete'));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Dashboard  $dashboard
     * @return mixed
     */
    public function restore(User $user, Dashboard $dashboard)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Dashboard  $dashboard
     * @return mixed
     */
    public function forceDelete(User $user, Dashboard $dashboard)
    {
        //
    }
}
