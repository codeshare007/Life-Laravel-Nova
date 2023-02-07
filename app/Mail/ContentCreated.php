<?php

namespace App\Mail;

use App\UserGeneratedContent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContentCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $userGeneratedContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserGeneratedContent $userGeneratedContent)
    {
        $this->userGeneratedContent = $userGeneratedContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->userGeneratedContent->name.' has been sent for review')
            ->markdown('emails.ugc.created')
            ->with($this->userGeneratedContent->toArray());
    }
}
