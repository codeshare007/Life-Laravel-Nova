<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Top()
 * @method static static Center()
 * @method static static Bottom()
 */
final class CardVerticalAlignment extends Enum
{
    const Top = 1;
    const Center = 2;
    const Bottom = 3;
}
