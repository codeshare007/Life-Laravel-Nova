<?php

namespace App\Mail;

use App\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuestionApprovedMail extends Mailable
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
        $url = url('/deeplink-to?path=' . urlencode('/questions/' . $this->question->firebase_document));

        return $this->subject('Your question has been approved!')
            ->markdown('emails.question.approved')
            ->with([
                'question' => $this->question,
                'user' => $this->question->user,
                'url' => $url,
            ]);
    }
}
