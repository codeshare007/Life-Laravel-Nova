<?php

namespace App\Services\GlobalStatus\Platforms;

use App\Services\GlobalStatus\Platform;
use App\Services\GlobalStatus\Contracts\MessageContract;
use App\Services\GlobalStatus\Messages\AndroidDynamicMessage;

class AndroidGlobalStatus extends Platform
{
    public function message(): MessageContract
    {
        return resolve(AndroidDynamicMessage::class);
    }
}
