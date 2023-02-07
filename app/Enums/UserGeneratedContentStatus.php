<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Private()
 * @method static static InReview()
 * @method static static Accepted()
 * @method static static Rejected()
 */
final class UserGeneratedContentStatus extends Enum
{
    const Private = 0;
    const InReview = 1;
    const Accepted = 2;
    const Rejected = 3;
}
