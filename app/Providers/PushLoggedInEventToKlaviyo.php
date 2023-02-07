<?php

namespace App\Providers;

use App\Events\UserLoggedInEvent;
use App\Services\Klaviyo\Events\LoggedIn;
use App\Services\Klaviyo\Klaviyo;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushLoggedInEventToKlaviyo
{
    /**
     * Handle the event.
     *
     * @param  UserLoggedInEvent  $event
     * @return void
     */
    public function handle(UserLoggedInEvent $event)
    {
        Klaviyo::event($event->user, new LoggedIn);
    }
}
