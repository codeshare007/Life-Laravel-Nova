<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static None()
 * @method static static Dark()
 * @method static static Light()
 */
final class CardOverlayStyle extends Enum
{
    const None = 1;
    const Dark = 2;
    const Light = 3;
}
