<?php

namespace App\Providers;

use App\Services\Klaviyo\Klaviyo;
use App\Events\UserSwitchedLanguage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Klaviyo\Events\SwitchedLanguage;

class PushSwitchedLanguageEventToKlaviyo
{
    /**
     * Handle the event.
     *
     * @param  UserSwitchedLanguage  $event
     * @return void
     */
    public function handle(UserSwitchedLanguage $event)
    {
        Klaviyo::event($event->user, new SwitchedLanguage($event->fromLanguage, $event->toLanguage));
    }
}
