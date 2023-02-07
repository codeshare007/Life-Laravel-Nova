<?php

namespace App\Enums\CommentReport;

use BenSampo\Enum\Enum;

/**
 * @method static static None()
 * @method static static Deleted()
 * @method static static Replaced()
 */
final class ActionTaken extends Enum
{
    const None = 0;
    const Deleted = 1;
    const Replaced = 2;
}
