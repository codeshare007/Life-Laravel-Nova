<?php

namespace App\Listeners;

use App\Mail\ContentApproved;
use Illuminate\Support\Facades\Mail;
use App\Events\ApprovedUserGeneratedContent;
use App\Services\ElementUpdateNotificationService;

class SendApprovedNotification
{
    /**
     * Handle the event.
     *
     * @param  ApprovedUserGeneratedContent  $event
     * @return void
     */
    public function handle(ApprovedUserGeneratedContent $event)
    {
        Mail::to($event->userGeneratedContent->user)->send(
            new ContentApproved($event->userGeneratedContent)
        );

        ElementUpdateNotificationService::sendUGCApproved($event->userGeneratedContent);
    }
}
