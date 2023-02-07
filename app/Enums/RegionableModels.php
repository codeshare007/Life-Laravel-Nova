<?php

namespace App\Enums;

use App\Blend;
use App\Oil;
use App\Supplement;
use BenSampo\Enum\Enum;

/**
 * @method static static Ailment()
 * @method static static Symptom()
 * @method static static BodySystem()
 */
final class RegionableModels extends Enum
{
    const Blend = Blend::class;
    const Oil = Oil::class;
    const Supplement = Supplement::class;
}
