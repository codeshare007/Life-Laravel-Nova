<?php

namespace App\Notifications\InApp;

use App\Blend;
use Illuminate\Support\Carbon;
use App\Notifications\InApp\Abstracts\MessageAndInternalButtonToDetailPageAlert;

class MessageAndInternalButtonToBlendDetailPageAlert extends MessageAndInternalButtonToDetailPageAlert
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $title, string $message, Blend $blend, string $buttonText, string $dismissText, ?Carbon $expiresAt)
    {
        parent::__construct($title, $message, Blend::class, $blend->uuid, $buttonText, $dismissText, $expiresAt);
    }

    public static function getSelectFieldModel(): string
    {
        return Blend::class;
    }

    public static function getSelectFieldAttributeName(): string
    {
        return 'blend';
    }
}
