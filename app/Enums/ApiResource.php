<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Ailment()
 * @method static static Symptom()
 * @method static static Oil()
 * @method static static Blend()
 * @method static static Supplement()
 * @method static static SupplementIngredient()
 * @method static static SolutionGroup()
 * @method static static Tag()
 * @method static static BodySystem()
 * @method static static Category()
 * @method static static Recipe()
 * @method static static RecipeIngredient()
 * @method static static Remedy()
 * @method static static Usage()
 * @method static static AilmentSolution()
 * @method static static Solution()
 */
final class ApiResource extends Enum
{
    const Ailment = 'AilmentResource';
    const Symptom = 'SymptomResource';
    const Oil = 'OilResource';
    const Blend = 'BlendResource';
    const Supplement = 'SupplementResource';
    const SupplementIngredient = 'SupplementIngredientResource';
    const SolutionGroup = 'SolutionGroupResource';
    const Tag = 'PropertyTagResource';
    const BodySystem = 'BodySystemResource';
    const Category = 'CategoryResource';
    const Recipe = 'RecipeResource';
    const RecipeIngredient = 'RecipeIngredientResource';
    const Remedy = 'RemedyResource';
    const Usage = 'UsageResource';
    const AilmentSolution = 'SolutionResource';
    const Solution = 'SolutionResource';
}
