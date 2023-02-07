<?php

namespace App\Notifications\InApp\Abstracts;

use Illuminate\Support\Carbon;
use Wqa\NovaExtendFields\Fields\Text;
use Wqa\NovaExtendFields\Fields\Select;
use Wqa\NovaExtendFields\Fields\DateTime;
use App\Notifications\InApp\Abstracts\InAppNotification;
use Christophrumpel\NovaNotifications\Contracts\HasCustomFields;

abstract class MessageAndInternalButtonToDetailPageAlert extends InAppNotification
{
    protected $title;
    protected $message;
    protected $modelType;
    protected $modelId;
    protected $buttonText;
    protected $dismissText;
    protected $expiresAt;

    abstract public static function getSelectFieldModel(): string;
    abstract public static function getSelectFieldAttributeName(): string;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $title, string $message, string $modelType, string $modelId, string $buttonText, string $dismissText, ?Carbon $expiresAt)
    {
        $this->title = $title;
        $this->message = $message;
        $this->modelType = $modelType;
        $this->modelId = $modelId;
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
    final public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'link_to_type' => class_basename($this->modelType),
            'link_to_id' => $this->modelId,
            'button_text' => $this->buttonText,
            'dismiss_text' => $this->dismissText,
        ];
    }

    final public function getExpiresAt(): ?Carbon
    {
        return $this->expiresAt;
    }

    final public static function fields()
    {
        return [
            Text::make('Title', 'title')->rules(['required']),

            Text::make('Message', 'message')->rules(['required']),

            static::modelSelectField(),

            Text::make('Button Text', 'buttonText')->rules(['required']),

            Text::make('Dismiss Text', 'dismissText')->rules(['required']),

            static::expiresAtField(),
        ];
    }

    protected static function modelSelectField()
    {
        $options = static::getSelectFieldModel()::all()->keyBy('id')->map(function ($item) {
            return $item->name ?? $item->id;
        });

        $label = title_case(str_replace('_', ' ', snake_case(static::getSelectFieldAttributeName())));
        $name = static::getSelectFieldAttributeName();

        return Select::make($label, $name)->options($options)->rules(['required']);
    }
}
