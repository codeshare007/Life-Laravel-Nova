<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static US()
 * @method static static Canada()
 * @method static static Mexico()
 * @method static static CostaRica()
 * @method static static Guatemala()
 * @method static static China()
 * @method static static HongKong()
 * @method static static Taiwan()
 * @method static static Japan()
 * @method static static Korea()
 * @method static static Malaysia()
 * @method static static Singapore()
 * @method static static EU()
 * @method static static Switzerland()
 * @method static static Israel()
 * @method static static Iceland()
 * @method static static Liechtenstein()
 * @method static static Norway()
 */
final class Region extends Enum
{
    const US = 0;
    const Canada = 1;
    const Mexico = 2;
    const CostaRica = 3;
    const Guatemala = 4;
    const China = 5;
    const HongKong = 6;
    const Taiwan = 7;
    const Japan = 8;
    const Korea = 9;
    const Malaysia = 10;
    const Singapore = 11;
    const EU = 12;
//    const Switzerland = 13;
//    const Israel = 14;
//    const Iceland = 15;
//    const Liechtenstein = 16;
//    const Norway = 17;
    const Australia = 18;
    const NewZealand = 19;

    public static function getDescription($value): string
    {
        if ($value === self::CostaRica) {
            return 'Costa Rica';
        }

        if ($value === self::HongKong) {
            return 'Hong Kong';
        }

        if ($value === self::NewZealand) {
            return 'New Zealand';
        }

        if ($value === self::US) {
            return 'US';
        }

        if ($value === self::EU) {
            return 'EU';
        }

        return parent::getDescription($value);
    }
}
