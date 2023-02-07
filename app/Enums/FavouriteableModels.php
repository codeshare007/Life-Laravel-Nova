<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Oil()
 * @method static static Blend()
 * @method static static Recipe()
 * @method static static Remedy()
 * @method static static BodySystem()
 * @method static static Ailment()
 * @method static static Symptom()
 * @method static static Category()
 * @method static static Supplement()
 * @method static static Tag()
 */
final class FavouriteableModels extends Enum
{
    const Oil = \App\Oil::class;
    const Blend = \App\Blend::class;
    const Recipe = \App\Recipe::class;
    const Remedy = \App\Remedy::class;
    const BodySystem = \App\BodySystem::class;
    const Ailment = \App\Ailment::class;
    const Symptom = \App\Ailment::class; // Swap when Symptom becomes its own model.
    const Category = \App\Category::class;
    const Supplement = \App\Supplement::class;
    const Tag = \App\Tag::class;
}
