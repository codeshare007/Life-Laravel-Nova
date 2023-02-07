<?php

namespace App\Events;

use App\Question;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class QuestionApprovedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $question;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
    }
}
