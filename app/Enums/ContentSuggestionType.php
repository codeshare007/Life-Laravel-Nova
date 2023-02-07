<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Ailment()
 * @method static static Symptom()
 * @method static static BodySystem()
 * @method static static RecipeCategory()
 */
final class ContentSuggestionType extends Enum
{
    const Ailment = 'Ailment';
    const Symptom = 'Symptom';
    const BodySystem = 'BodySystem';
    const RecipeCategory = 'RecipeCategory';
}
