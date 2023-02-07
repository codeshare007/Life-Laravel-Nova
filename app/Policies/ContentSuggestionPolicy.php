<?php

namespace App\Policies;

use App\User;
use App\ContentSuggestion;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentSuggestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user generated content.
     *
     * @param  \App\User  $user
     * @param  \App\ContentSuggestion  $suggestion
     * @return mixed
     */
    public function view(User $user, ContentSuggestion $suggestion)
    {

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
     * @param  \App\ContentSuggestion  $suggestion
     * @return mixed
     */
    public function update(User $user, ContentSuggestion $suggestion)
    {

    }

    /**
     * Determine whether the user can delete the user generated content.
     *
     * @param  \App\User  $user
     * @param  \App\ContentSuggestion  $suggestion
     * @return mixed
     */
    public function delete(User $user, ContentSuggestion $suggestion)
    {

    }
}