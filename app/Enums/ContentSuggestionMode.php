<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Suggestion()
 * @method static static Association()
 */
final class ContentSuggestionMode extends Enum
{
    const Suggestion = 'suggestion';
    const Association = 'association';
}
