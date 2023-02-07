<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Oil()
 * @method static static Blend()
 * @method static static Supplement()
 */
final class SolutionType extends Enum
{
    const Oil = 0;
    const Blend = 1;
    const Supplement = 2;
}
