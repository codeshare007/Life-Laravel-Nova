<?php

namespace App\Services\Klaviyo\Events;

interface Event
{
    public function name(): string;
    public function properties(): array;
}
