<?php

namespace App\Events;

use App\User;
use App\Enums\UserLanguage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserSwitchedLanguage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $fromLanguage;
    public $toLanguage;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, UserLanguage $fromLanguage, UserLanguage $toLanguage)
    {
        $this->user = $user;
        $this->fromLanguage = $fromLanguage;
        $this->toLanguage = $toLanguage;
    }
}
