<?php

namespace App\Policies;

use App\User;
use App\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
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
     * @param  \App\Video  $video
     * @return mixed
     */
    public function view(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.video-list'));
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
        return $user->check_permission_access(config('permissions.access.video-add'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Video  $video
     * @return mixed
     */
    public function update(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.video-edit'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Video  $video
     * @return mixed
     */
    public function delete(User $user)
    {
        //
        return $user->check_permission_access(config('permissions.access.video-delete'));
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
        return $user->check_permission_access(config('permissions.access.video-action'));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Video  $video
     * @return mixed
     */
    public function restore(User $user, Video $video)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Video  $video
     * @return mixed
     */
    public function forceDelete(User $user, Video $video)
    {
        //
    }
}
