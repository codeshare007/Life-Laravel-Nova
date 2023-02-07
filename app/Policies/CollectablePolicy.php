<?php

namespace App\Policies;

use App\User;
use App\Collection;
use App\Collectable;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectablePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the collectable.
     *
     * @param  \App\User  $user
     * @param  \App\Collectable  $collectable
     * @return mixed
     */
    public function view(User $user, Collectable $collectable)
    {
        //
    }

    /**
     * Determine whether the user can create collectables.
     *
     * @param  \App\User  $user
     * @param  \App\Collection  $collection
     * @return mixed
     */
    public function create(User $user, Collection $collection)
    {
        return (int)$user->id === (int)$collection->user_id;
    }

    /**
     * Determine whether the user can update the collectable.
     *
     * @param  \App\User  $user
     * @param  \App\Collectable  $collectable
     * @return mixed
     */
    public function update(User $user, Collectable $collectable)
    {
        //
    }

    /**
     * Determine whether the user can delete the collectable.
     *
     * @param  \App\User  $user
     * @param  \App\Collectable  $collectable
     * @return mixed
     */
    public function delete(User $user, Collection $collection, Collectable $collectable)
    {
        return (int)$user->id === (int)$collection->user_id;
    }
}
