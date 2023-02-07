<?php

namespace App\Enums\Question;

use BenSampo\Enum\Enum;

/**
 * @method static static InReview()
 * @method static static Approved()
 * @method static static Rejected()
 */
final class Status extends Enum
{
    const InReview = 1;
    const Approved = 2;
    const Rejected = 3;
}
