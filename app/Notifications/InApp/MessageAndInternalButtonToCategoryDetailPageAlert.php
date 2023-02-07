<?php

namespace App\Notifications\InApp;

use App\Category;
use Illuminate\Support\Carbon;
use App\Notifications\InApp\Abstracts\MessageAndInternalButtonToDetailPageAlert;

class MessageAndInternalButtonToCategoryDetailPageAlert extends MessageAndInternalButtonToDetailPageAlert
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $title, string $message, Category $category, string $buttonText, string $dismissText, ?Carbon $expiresAt)
    {
        parent::__construct($title, $message, Category::class, $category->uuid, $buttonText, $dismissText, $expiresAt);
    }

    public static function getSelectFieldModel(): string
    {
        return Category::class;
    }

    public static function getSelectFieldAttributeName(): string
    {
        return 'category';
    }
}
