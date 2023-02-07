<?php

namespace App\Mail;

use App\ContentSuggestion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContentSuggested extends Mailable
{
    use Queueable, SerializesModels;

    protected $contentSuggestion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ContentSuggestion $contentSuggestion)
    {
        $this->contentSuggestion = $contentSuggestion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->contentSuggestion->name.' has been sent for review')
            ->markdown('emails.ugc.suggested')
            ->with($this->contentSuggestion->toArray());
    }
}
