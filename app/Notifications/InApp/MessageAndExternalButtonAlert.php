<?php

namespace App\Notifications\InApp;

use Illuminate\Support\Carbon;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\DateTime;
use App\Notifications\InApp\Abstracts\InAppNotification;
use Christophrumpel\NovaNotifications\Contracts\HasCustomFields;

class MessageAndExternalButtonAlert extends InAppNotification
{
    protected $title;
    protected $message;
    protected $buttonUrl;
    protected $buttonText;
    protected $dismissText;
    protected $expiresAt;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $title, string $message, string $buttonUrl, string $buttonText, string $dismissText, ?Carbon $expiresAt)
    {
        $this->title = $title;
        $this->message = $message;
        $this->buttonUrl = $buttonUrl;
        $this->buttonText = $buttonText;
        $this->dismissText = $dismissText;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'button_url' => $this->buttonUrl,
            'button_text' => $this->buttonText,
            'dismiss_text' => $this->dismissText,
        ];
    }

    public function getExpiresAt(): ?Carbon
    {
        return $this->expiresAt;
    }

    public static function fields() {
        return [
            Text::make('Title', 'title')->rules(['required']),

            Text::make('Message', 'message')->rules(['required']),

            Text::make('Button URL', 'buttonUrl')->rules([['required'], 'url']),

            Text::make('Button Text', 'buttonText')->rules(['required']),

            Text::make('Dismiss Text', 'dismissText')->rules(['required']),

            static::expiresAtField(),
        ];
    }
}
