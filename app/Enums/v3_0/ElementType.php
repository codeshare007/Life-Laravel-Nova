<?php

namespace App\Enums\v3_0;

use App\Oil;
use App\Tag;
use App\Blend;
use App\Usage;
use App\Recipe;
use App\Remedy;
use App\Ailment;
use App\Category;
use App\Solution;
use App\BodySystem;
use App\Supplement;
use BenSampo\Enum\Enum;
use App\AilmentSolution;
use App\RecipeIngredient;
use App\RemedyIngredient;
use App\SupplementIngredient;

/**
 * @method static static Oil()
 * @method static static Blend()
 * @method static static Supplement()
 * @method static static Ailment()
 * @method static static Recipe()
 * @method static static Remedy()
 * @method static static BodySystem()
 * @method static static Tag()
 * @method static static SupplementIngredient()
 * @method static static RecipeIngredient()
 * @method static static RemedyIngredient()
 * @method static static AilmentSolution()
 * @method static static Solution()
 * @method static static Category()
 * @method static static Usage()
 */
final class ElementType extends Enum
{
    const Oil = Oil::class;
    const Blend = Blend::class;
    const Supplement = Supplement::class;
    const Ailment = Ailment::class;
    const Recipe = Recipe::class;
    const Remedy = Remedy::class;
    const BodySystem = BodySystem::class;
    const Tag = Tag::class;
    const SupplementIngredient = SupplementIngredient::class;
    const RecipeIngredient = RecipeIngredient::class;
    const RemedyIngredient = RemedyIngredient::class;
    const AilmentSolution = AilmentSolution::class;
    const Solution = Solution::class;
    const Category = Category::class;
    const Usage = Usage::class;
}

