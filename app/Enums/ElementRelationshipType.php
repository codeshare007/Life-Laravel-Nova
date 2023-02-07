<?php

namespace App\Enums;

use App\Favourite;
use App\SolutionGroup;
use BenSampo\Enum\Enum;
use App\AilmentSolution;
use App\RecipeIngredient;
use App\SupplementIngredient;
use App\AilmentSecondarySolution;

/**
 * @method static static AilmentSolution()
 * @method static static AilmentSecondarySolution()
 * @method static static RecipeIngredient()
 * @method static static SolutionGroup()
 * @method static static SupplementIngredient()
 * @method static static Favourite()
 */
final class ElementRelationshipType extends Enum
{
    const AilmentSolution = AilmentSolution::class;
    const AilmentSecondarySolution = AilmentSecondarySolution::class;
    const RecipeIngredient = RecipeIngredient::class;
    const SolutionGroup = SolutionGroup::class;
    const SupplementIngredient = SupplementIngredient::class;
    const Favourite = Favourite::class;
}
