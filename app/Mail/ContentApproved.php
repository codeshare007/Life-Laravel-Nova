<?php

namespace App\Mail;

use App\UserGeneratedContent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContentApproved extends Mailable
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
        $url = 'https://api1.essentiallife.com/deeplink-resource/' . strtolower($this->userGeneratedContent->type) . '/' . $this->userGeneratedContent->id;

        return $this->subject($this->userGeneratedContent->name.' has been approved!')
            ->markdown('emails.ugc.approved')
            ->with([
                'type' => $this->userGeneratedContent->type,
                'user' => $this->userGeneratedContent->user,
                'url' => $url,
            ]);
    }
}
