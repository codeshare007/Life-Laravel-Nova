<?php

namespace App\Policies;

use App\User;
use App\Favourite;
use Illuminate\Auth\Access\HandlesAuthorization;

class FavouritePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the favourite.
     *
     * @param  \App\User  $user
     * @param  \App\Favourite  $favourite
     * @return mixed
     */
    public function view(User $user, Favourite $favourite)
    {
        //
    }

    /**
     * Determine whether the user can create favourites.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the favourite.
     *
     * @param  \App\User  $user
     * @param  \App\Favourite  $favourite
     * @return mixed
     */
    public function update(User $user, Favourite $favourite)
    {
        //
    }

    /**
     * Determine whether the user can delete the favourite.
     *
     * @param  \App\User  $user
     * @param  \App\Favourite  $favourite
     * @return mixed
     */
    public function delete(User $user, Favourite $favourite)
    {
        return (int)$user->id === (int)$favourite->user_id;
    }
}
