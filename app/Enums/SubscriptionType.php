<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static None()
 * @method static static TheEssentialLifeMembershipLegacy()
 * @method static static TheEssentialLifeMembership12Month()
 * @method static static TheEssentialLifeMembershipTrial()
 */
final class SubscriptionType extends Enum
{
    const None = 1;
    const TheEssentialLifeMembershipLegacy = 2;
    const TheEssentialLifeMembership12Month = 3;
    const TheEssentialLifeMembershipTrial = 4;

    public static function getDescription($value): string
    {
        if ($value === self::TheEssentialLifeMembershipLegacy) {
            return 'Legacy';
        }

        if ($value === self::TheEssentialLifeMembership12Month) {
            return 'Annual';
        }

        if ($value === self::TheEssentialLifeMembershipTrial) {
            return 'Trial';
        }

        return parent::getDescription($value);
    }
}
