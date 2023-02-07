<?php

namespace App\Policies;

use App\User;
use App\Like;
use Illuminate\Auth\Access\HandlesAuthorization;

class LikePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the like.
     *
     * @param  \App\User  $user
     * @param  \App\Like  $like
     * @return mixed
     */
    public function view(User $user, Like $like)
    {
        //
    }

    /**
     * Determine whether the user can create likes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the like.
     *
     * @param  \App\User  $user
     * @param  \App\Like  $like
     * @return mixed
     */
    public function update(User $user, Like $like)
    {
        //
    }

    /**
     * Determine whether the user can delete the like.
     *
     * @param  \App\User  $user
     * @param  \App\Like  $like
     * @return mixed
     */
    public function delete(User $user, Like $like)
    {
        return (int)$user->id === (int)$like->user_id;
    }
}
