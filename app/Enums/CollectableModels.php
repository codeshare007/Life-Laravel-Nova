<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Oil()
 * @method static static Blend()
 * @method static static Recipe()
 * @method static static Ailment()
 * @method static static Remedy()
 * @method static static BodySystem()
 * @method static static Solution()
 */
final class CollectableModels extends Enum
{
    const Oil = \App\Oil::class;
    const Blend = \App\Blend::class;
    const Recipe = \App\Recipe::class;
    const Ailment = \App\Ailment::class;
    const Remedy = \App\Remedy::class;
    const BodySystem = \App\BodySystem::class;
    const Solution = \App\Solution::class;
}
