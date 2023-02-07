<?php

namespace App\Services\Klaviyo\Events;

class LoggedIn implements Event
{
    public function name(): string
    {
        return "Logged in";
    }

    public function properties(): array
    {
        return [];
    }
}
