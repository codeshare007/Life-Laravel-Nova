<?php

namespace App\Listeners;

use App\Mail\QuestionApprovedMail;
use Illuminate\Support\Facades\Mail;
use App\Events\QuestionApprovedEvent;

class SendQuestionApprovedNotification
{
    /**
     * Handle the event.
     *
     * @param  QuestionApprovedEvent  $event
     * @return void
     */
    public function handle(QuestionApprovedEvent $event)
    {
        Mail::to($event->question->user)->send(
            new QuestionApprovedMail($event->question)
        );
    }
}
