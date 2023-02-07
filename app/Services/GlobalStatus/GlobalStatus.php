<?php

namespace App\Services\GlobalStatus;

use App\Enums\Platform;
use App\Enums\UserLanguage;
use App\Services\GlobalStatus\Contracts\MessageContract;
use App\Services\GlobalStatus\Platforms\iOSGlobalStatus;
use App\Services\GlobalStatus\Platforms\AndroidGlobalStatus;

class GlobalStatus
{
    public function for(Platform $platform, $version, UserLanguage $language): MessageContract
    {
        if ($platform->is(Platform::iOS)) {
            return (new iOSGlobalStatus($version, $language))->message();
        }

        if ($platform->is(Platform::Android)) {
            return (new AndroidGlobalStatus($version, $language))->message();
        }
    }
}
