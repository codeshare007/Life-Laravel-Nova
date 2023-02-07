<?php

namespace App\Enums;

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
use App\SourcingMethod;
use BenSampo\Enum\Enum;
use App\AilmentSolution;
use App\RecipeIngredient;
use App\RemedyIngredient;
use App\SupplementIngredient;
use App\UserGeneratedContent;

/**
 * @method static static Ailment()
 * @method static static AilmentSolution()
 * @method static static Blend()
 * @method static static BodySystem()
 * @method static static Category()
 * @method static static Oil()
 * @method static static Recipe()
 * @method static static RecipeIngredient()
 * @method static static Remedy()
 * @method static static RemedyIngredient()
 * @method static static Solution()
 * @method static static SourcingMethod()
 * @method static static Supplement()
 * @method static static SupplementIngredient()
 * @method static static Tag()
 * @method static static Usage()
 * @method static static UserGeneratedContent()
 */
final class ElementType extends Enum
{
    const Ailment = Ailment::class;
    const AilmentSolution = AilmentSolution::class;
    const Blend = Blend::class;
    const BodySystem = BodySystem::class;
    const Category = Category::class;
    const Oil = Oil::class;
    const Recipe = Recipe::class;
    const RecipeIngredient = RemedyIngredient::class;
    const Remedy = Remedy::class;
    const RemedyIngredient = RecipeIngredient::class;
    const Solution = Solution::class;
    const SourcingMethod = SourcingMethod::class;
    const Supplement = Supplement::class;
    const SupplementIngredient = SupplementIngredient::class;
    const Tag = Tag::class;
    const Usage = Usage::class;
    const UserGeneratedContent = UserGeneratedContent::class;
}
