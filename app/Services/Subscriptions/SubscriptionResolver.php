<?php

namespace App\Services\Subscriptions;

use App\Enums\Platform;
use App\Services\Subscriptions\Android\AndroidPlatform;
use App\Services\Subscriptions\Contracts\PlatformContract;
use App\Services\Subscriptions\Contracts\SubscriptionModelContract;
use App\Services\Subscriptions\iOS\iOSPlatform;

class SubscriptionResolver
{
    public function get(string $receipt, Platform $platform): SubscriptionModelContract
    {
        return $this->service($platform)->get($receipt);
    }

    protected function service(Platform $platform): PlatformContract
    {
        if ($platform->is(Platform::iOS)) {
            return new iOSPlatform();
        }

        if ($platform->is(Platform::Android)) {
            return new AndroidPlatform();
        }
    }
}