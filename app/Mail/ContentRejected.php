<?php

namespace App\Mail;

use App\UserGeneratedContent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContentRejected extends Mailable
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
        return $this->subject('An update on your recently submitted ' . $this->userGeneratedContent->name)
            ->markdown('emails.ugc.rejected')
            ->with([
                'type' => $this->userGeneratedContent->type,
                'name' => $this->userGeneratedContent->name,
                'user' => $this->userGeneratedContent->user,
                'rejection_reason_subject' => $this->userGeneratedContent->rejection_reason_subject,
                'rejection_reason_description' => $this->userGeneratedContent->rejection_reason_description,
            ]);
    }
}
