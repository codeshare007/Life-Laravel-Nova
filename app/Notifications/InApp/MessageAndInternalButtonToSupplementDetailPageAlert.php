<?php

namespace App\Notifications\InApp;

use App\Supplement;
use Illuminate\Support\Carbon;
use App\Notifications\InApp\Abstracts\MessageAndInternalButtonToDetailPageAlert;

class MessageAndInternalButtonToSupplementDetailPageAlert extends MessageAndInternalButtonToDetailPageAlert
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $title, string $message, Supplement $supplement, string $buttonText, string $dismissText, ?Carbon $expiresAt)
    {
        parent::__construct($title, $message, Supplement::class, $supplement->uuid, $buttonText, $dismissText, $expiresAt);
    }

    public static function getSelectFieldModel(): string
    {
        return Supplement::class;
    }

    public static function getSelectFieldAttributeName(): string
    {
        return 'supplement';
    }
}
