<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Mention()
 * @method static static Activity()
 * @method static static UgcStatus()
 * @method static static Element()
 */
final class PushNotificationType extends Enum
{
    const Mention = 'mention';
    const Activity = 'activity';
    const UgcStatus = 'ugc_status';
    const Element = 'element';
}
