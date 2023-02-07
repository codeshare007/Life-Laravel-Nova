<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Property()
 * @method static static Constituent()
 */
final class TagType extends Enum
{
    const Property = 0;
    const Constituent = 1;
}
