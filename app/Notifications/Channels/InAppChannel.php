<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Notifications\InApp\Abstracts\InAppNotification;
use Illuminate\Notifications\Channels\DatabaseChannel;

class InAppChannel extends DatabaseChannel
{
	public function addDataToPayload(InAppNotification $notification, array $payload)
	{
		$payload['expires_at'] = $notification->getExpiresAt() === null ? null : $notification->getExpiresAt()->toDateTimeString();

		return $payload;
	}

	/**
	 * Build an array payload for the DatabaseNotification Model.
	 *
	 * @param  mixed  $notifiable
	 * @param  \Illuminate\Notifications\InAppNotification  $notification
	 * @return array
	 */
	protected function buildPayload($notifiable, Notification $notification)
	{
		$payload = parent::buildPayload($notifiable, $notification);
		$payload = $this->addDataToPayload($notification, $payload);

		return $payload;
	}
}