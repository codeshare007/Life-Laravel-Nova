<?php

namespace App\Notifications\InApp;

use App\Oil;
use Illuminate\Support\Carbon;
use App\Notifications\InApp\Abstracts\MessageAndInternalButtonToDetailPageAlert;

class MessageAndInternalButtonToOilDetailPageAlert extends MessageAndInternalButtonToDetailPageAlert
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $title, string $message, Oil $oil, string $buttonText, string $dismissText, ?Carbon $expiresAt)
    {
        parent::__construct($title, $message, Oil::class, $oil->uuid, $buttonText, $dismissText, $expiresAt);
    }

    public static function getSelectFieldModel(): string
    {
        return Oil::class;
    }

    public static function getSelectFieldAttributeName(): string
    {
        return 'oil';
    }
}
