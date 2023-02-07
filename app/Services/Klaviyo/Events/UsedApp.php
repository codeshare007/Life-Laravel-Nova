<?php

namespace App\Services\Klaviyo\Events;

class UsedApp implements Event
{
    public function name(): string
    {
        return "Used app";
    }

    public function properties(): array
    {
        return [];
    }
}
