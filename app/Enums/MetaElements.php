<?php

namespace App\Enums;

use App\Ailment;
use App\Blend;
use App\BodySystem;
use App\Category;
use App\Oil;
use App\Recipe;
use App\Remedy;
use App\Supplement;
use App\Tag;
use BenSampo\Enum\Enum;

/**
 * @method static static Ailment()
 * @method static static Blend()
 * @method static static BodySystem()
 * @method static static Category()
 * @method static static Oil()
 * @method static static Recipe()
 * @method static static Remedy()
 * @method static static Supplement()
 * @method static static Tag()
 */
final class MetaElements extends Enum
{
    const Ailment = Ailment::class;
    const Blend = Blend::class;
    const BodySystem = BodySystem::class;
    const Category = Category::class;
    const Oil = Oil::class;
    const Recipe = Recipe::class;
    const Remedy = Remedy::class;
    const Supplement = Supplement::class;
    const Tag = Tag::class;
}
