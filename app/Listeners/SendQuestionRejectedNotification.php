<?php

namespace App\Listeners;

use App\Mail\QuestionRejectedMail;
use Illuminate\Support\Facades\Mail;
use App\Events\QuestionRejectedEvent;

class SendQuestionRejectedNotification
{
    /**
     * Handle the event.
     *
     * @param  QuestionRejectedEvent  $event
     * @return void
     */
    public function handle(QuestionRejectedEvent $event)
    {
        Mail::to($event->question->user)->send(
            new QuestionRejectedMail($event->question)
        );
    }
}
