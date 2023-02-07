<?php

namespace App\Enums\CommentReport;

use BenSampo\Enum\Enum;

/**
 * @method static static Open()
 * @method static static Resolved()
 */
final class Status extends Enum
{
    const Open =   0;
    const Resolved =   1;
}
