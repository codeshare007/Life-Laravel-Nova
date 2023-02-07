<?php

namespace App\Policies;

use App\User;
use Illuminate\Notifications\DatabaseNotification as Notification;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the notification.
     *
     * @param  \App\User  $user
     * @param  Illuminate\Notifications\DatabaseNotification  $notification
     * @return mixed
     */
    public function view(User $user, Notification $notification)
    {
        //
    }

    /**
     * Determine whether the user can create notifications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the notification.
     *
     * @param  \App\User  $user
     * @param  Illuminate\Notifications\DatabaseNotification  $notification
     * @return mixed
     */
    public function update(User $user, Notification $notification)
    {
        return 
            $notification->notifiable_type === User::class &&
            (int)$user->id === (int)$notification->notifiable_id;
    }

    /**
     * Determine whether the user can delete the notification.
     *
     * @param  \App\User  $user
     * @param  Illuminate\Notifications\DatabaseNotification  $notification
     * @return mixed
     */
    public function delete(User $user, Notification $notification)
    {
        //
    }
}
