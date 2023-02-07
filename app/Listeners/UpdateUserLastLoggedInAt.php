<?php

namespace App\Listeners;

use App\Events\UserLoggedInEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateUserLastLoggedInAt
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  UserLoggedInEvent  $event
     * @return void
     */
    public function handle(UserLoggedInEvent $event)
    {
        $event->user->last_logged_in_at = now();
        $event->user->save();
    }
}
