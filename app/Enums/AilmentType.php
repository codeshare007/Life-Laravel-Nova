<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Ailment()
 * @method static static Symptom()
 */
final class AilmentType extends Enum
{
    const Ailment = 0;
    const Symptom = 1;
}
