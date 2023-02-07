<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static iOS()
 * @method static static Android()
 */
final class Platform extends Enum
{
    const iOS = 'ios';
    const Android = 'android';

    public static function getDescription($value): string
    {
        if ($value === self::iOS) {
            return 'iOS';
        }

        return parent::getDescription($value);
    }
}
