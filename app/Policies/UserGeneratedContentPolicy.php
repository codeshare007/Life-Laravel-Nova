<?php

namespace App\Policies;

use App\Enums\UserGeneratedContentStatus;
use App\User;
use App\UserGeneratedContent;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserGeneratedContentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user generated content.
     *
     * @param  \App\User  $user
     * @param  \App\UserGeneratedContent  $ugc
     * @return mixed
     */
    public function view(User $user, UserGeneratedContent $ugc)
    {
        return ($ugc->status !== UserGeneratedContentStatus::Accepted) && (int)$user->id === (int)$ugc->user_id;
    }

    /**
     * Determine whether the user can create user generated content.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        
    }

    /**
     * Determine whether the user can update the user generated content.
     *
     * @param  \App\User  $user
     * @param  \App\UserGeneratedContent  $ugc
     * @return mixed
     */
    public function update(User $user, UserGeneratedContent $ugc)
    {
        return ($ugc->status !== UserGeneratedContentStatus::Accepted) && (int)$user->id === (int)$ugc->user_id;
    }

    /**
     * Determine whether the user can delete the user generated content.
     *
     * @param  \App\User  $user
     * @param  \App\UserGeneratedContent  $ugc
     * @return mixed
     */
    public function delete(User $user, UserGeneratedContent $ugc)
    {
        return (int) $user->id === (int) $ugc->user_id;
    }
}
