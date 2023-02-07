<?php

namespace App\Listeners;

use App\Mail\ContentRejected;
use Illuminate\Support\Facades\Mail;
use App\Events\RejectedUserGeneratedContent;
use App\Services\ElementUpdateNotificationService;

class SendRejectedNotification
{
    /**
     * Handle the event.
     *
     * @param  RejectedUserGeneratedContent  $event
     * @return void
     */
    public function handle(RejectedUserGeneratedContent $event)
    {
        Mail::to($event->userGeneratedContent->user)->send(
            new ContentRejected($event->userGeneratedContent)
        );

        ElementUpdateNotificationService::sendUGCRejected($event->userGeneratedContent);
    }
}
