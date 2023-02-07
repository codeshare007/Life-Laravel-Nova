<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Ailment()
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
 * @method static static Symptom()
 * @method static static Tag()
 * @method static static Usage()
 * @method static static UserGeneratedContent()
 */
final class ElementCacheKey extends Enum
{
    const Ailment = 'Ailment';
    const Blend = 'Blend';
    const BodySystem = 'BodySystem';
    const Category = 'Category';
    const Oil = 'Oil';
    const Recipe = 'Recipe';
    const RecipeIngredient = 'RemedyIngredient';
    const Remedy = 'Remedy';
    const RemedyIngredient = 'RecipeIngredient';
    const Solution = 'Solution';
    const SourcingMethod = 'SourcingMethod';
    const Supplement = 'Supplement';
    const SupplementIngredient = 'SupplementIngredient';
    const Symptom = 'Symptom';
    const Tag = 'Tag';
    const Usage = 'Usage';
    const UserGeneratedContent = 'UserGeneratedContent';
}
