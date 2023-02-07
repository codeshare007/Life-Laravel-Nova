<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static English()
 * @method static static Spanish()
 * @method static static Portugese()
 * @method static static Japanese()
 */
final class UserLanguage extends Enum
{
    const English = 'en';
    const Spanish = 'es';
    const Portugese = 'pt';
    const Japanese = 'ja';
}
