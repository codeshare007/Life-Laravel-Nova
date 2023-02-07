<?php

namespace App\Providers;

use App\Events\UserUsedAppEvent;
use App\Services\Klaviyo\Klaviyo;
use App\Services\Klaviyo\Events\UsedApp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushUsedAppEventToKlaviyo
{
    /**
     * Handle the event.
     *
     * @param  UserUsedAppEvent  $event
     * @return void
     */
    public function handle(UserUsedAppEvent $event)
    {
        Klaviyo::event($event->user, new UsedApp);
    }
}
