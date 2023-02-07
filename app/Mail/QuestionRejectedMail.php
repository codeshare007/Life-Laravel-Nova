<?php

namespace App\Mail;

use App\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuestionRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $question;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('An update on your recently submitted question')
            ->markdown('emails.question.rejected')
            ->with([
                'question' => $this->question,
                'user' => $this->question->user,
                'rejection_reason_subject' => $this->question->rejection_reason_subject,
                'rejection_reason_description' => $this->question->rejection_reason_description,
            ]);
    }
}
