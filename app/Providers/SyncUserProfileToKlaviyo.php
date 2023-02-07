<?php

namespace App\Providers;

use App\Events\UserUsedAppEvent;
use App\Events\UserLoggedInEvent;
use App\Services\Klaviyo\Klaviyo;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncUserProfileToKlaviyo
{
    /**
     * Handle the event.
     *
     * @param  UserUsedAppEvent|UserLoggedInEvent  $event
     * @return void
     */
    public function handle($event)
    {
        Klaviyo::syncUserProfile($event->user);
    }
}
