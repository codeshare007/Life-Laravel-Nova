<?php

namespace App\Notifications\InApp;

use App\BodySystem;
use Illuminate\Support\Carbon;
use App\Notifications\InApp\Abstracts\MessageAndInternalButtonToDetailPageAlert;

class MessageAndInternalButtonToBodySystemDetailPageAlert extends MessageAndInternalButtonToDetailPageAlert
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $title, string $message, BodySystem $bodySystem, string $buttonText, string $dismissText, ?Carbon $expiresAt)
    {
        parent::__construct($title, $message, BodySystem::class, $bodySystem->uuid, $buttonText, $dismissText, $expiresAt);
    }

    public static function getSelectFieldModel(): string
    {
        return BodySystem::class;
    }

    public static function getSelectFieldAttributeName(): string
    {
        return 'bodySystem';
    }
}
