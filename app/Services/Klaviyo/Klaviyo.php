<?php

namespace App\Services\Klaviyo;

use App\User;
use App\Services\Klaviyo\Events\Event;

class Klaviyo
{
    protected static function isEnabled(): bool
    {
        return !!config('services.klaviyo.public_key');
    }

    public static function syncUserProfile(User $user): bool
    {
        if (!self::isEnabled()) {
            return false;
        }

        return (new KlaviyoApi)->identifyProfile($user->email, $user->klaviyoProfile());
    }

    public static function event(User $user, Event $event): bool
    {
        if (!self::isEnabled()) {
            return false;
        }

        return (new KlaviyoApi)->trackProfileActivity($user->email, $event->name(), $event->properties());
    }
}
