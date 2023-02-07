<?php

namespace App\Services\Subscriptions\Contracts;

interface PlatformContract
{
    public function get(string $receipt): SubscriptionModelContract;
}
