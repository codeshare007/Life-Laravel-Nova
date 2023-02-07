<?php

namespace App\Notifications\InApp\Abstracts;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Wqa\NovaExtendFields\Fields\DateTime;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Channels\InAppChannel;

abstract class InAppNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    final public function via($notifiable)
    {
        return [
            InAppChannel::class
        ];
    }

    final public static function expiresAtField()
    {
        return DateTime::make('Expires At', 'expiresAt')->nullable()->rules([
            'nullable',
            'date',
            'date_format:Y-m-d H:i:s',
            'after:' . now()->toDateTimeString(),
        ])->withMeta([
            'value' => null,
            'placeholder' => 'No expiry'
        ]);
    }

    abstract public function toArray($notifiable);

    abstract public function getExpiresAt(): ?Carbon;
}
