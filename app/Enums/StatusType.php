<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Ok()
 * @method static static Warning()
 * @method static static Error()
 */
final class StatusType extends Enum
{
    const Ok = 'ok';
    const Warning = 'warning';
    const Error = 'error';
}
