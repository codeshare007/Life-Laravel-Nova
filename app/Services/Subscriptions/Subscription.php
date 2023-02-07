<?php

namespace App\Services\Subscriptions;

use App\Enums\Platform;
use Illuminate\Support\Facades\Facade;
use App\Services\Subscriptions\SubscriptionResolver;
use App\Services\Subscriptions\Contracts\SubscriptionModelContract;

/**
 * @method static SubscriptionModelContract get(string $receipt, Platform $platform)
 *
 * @see SubscriptionResolver
 */
class Subscription extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SubscriptionResolver::class;
    }
}