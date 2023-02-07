<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Left()
 * @method static static Center()
 * @method static static Right()
 */
final class CardHorizontalAlignment extends Enum
{
    const Left = 1;
    const Center = 2;
    const Right = 3;
}
