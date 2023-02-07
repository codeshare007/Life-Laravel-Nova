<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Oil()
 * @method static static Blend()
 * @method static static Recipe()
 * @method static static Remedy()
 * @method static static Usage()
 * @method static static BodySystem()
 * @method static static Solution()
 * @method static static Ailment()
 * @method static static Category()
 * @method static static Collection()
 */
final class ViewableModels extends Enum
{
    const Oil = \App\Oil::class;
    const Blend = \App\Blend::class;
    const Recipe = \App\Recipe::class;
    const Remedy = \App\Remedy::class;
    const Usage = \App\Usage::class;
    const BodySystem = \App\BodySystem::class;
    const Solution = \App\Solution::class;
    const Ailment = \App\Ailment::class;
    const Category = \App\Category::class;
    const Collection = \App\Collection::class;
}
