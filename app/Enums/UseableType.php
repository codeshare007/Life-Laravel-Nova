<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Oil()
 * @method static static Blend()
 */
final class UseableType extends Enum
{
    const Oil = \App\Oil::class;
    const Blend = \App\Blend::class;
}
