<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Ailment()
 * @method static static Symptom()
 * @method static static Oil()
 * @method static static Blend()
 * @method static static Supplement()
 * @method static static SolutionGroup()
 * @method static static Tag()
 * @method static static BodySystem()
 * @method static static Category()
 * @method static static Recipe()
 * @method static static Remedy()
 */
final class ApiCollectionResource extends Enum
{
    const Ailment = 'AilmentResourceCollection';
    const Symptom = 'SymptomResourceCollection';
    const Oil = 'OilResourceCollection';
    const Blend = 'BlendResourceCollection';
    const Supplement = 'SupplementResourceCollection';
    const SolutionGroup = 'SolutionGroupResourceCollection';
    const Tag = 'PropertyTagResourceCollection';
    const BodySystem = 'BodySystemResourceCollection';
    const Category = 'CategoryResourceCollection';
    const Recipe = 'RecipeResourceCollection';
    const Remedy = 'RemedyResourceCollection';
}
