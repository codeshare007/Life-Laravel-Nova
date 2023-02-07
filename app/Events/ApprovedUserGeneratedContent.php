<?php

namespace App\Events;

use App\UserGeneratedContent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ApprovedUserGeneratedContent
{
    use Dispatchable, SerializesModels;

    public $userGeneratedContent;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UserGeneratedContent $userGeneratedContent)
    {
        $this->userGeneratedContent = $userGeneratedContent->load('user');
    }
}
