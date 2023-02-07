<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static RealTime()
 * @method static static Daily()
 * @method static static Weekly()
 */
final class NotificationFrequency extends Enum
{
    const RealTime = 0;
    const Daily = 1;
    const Weekly = 2;
}
