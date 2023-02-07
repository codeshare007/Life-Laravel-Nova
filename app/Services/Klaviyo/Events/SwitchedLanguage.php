<?php

namespace App\Services\Klaviyo\Events;

use App\Enums\UserLanguage;

class SwitchedLanguage implements Event
{
    protected $fromLanguage;
    protected $toLanguage;
    
    public function __construct(UserLanguage $fromLanguage, UserLanguage $toLanguage)
    {
        $this->fromLanguage = $fromLanguage;
        $this->toLanguage = $toLanguage;
    }

    public function name(): string
    {
        return "Switched language";
    }

    public function properties(): array
    {
        return [
            'from_language' => $this->fromLanguage->key,
            'to_language' => $this->toLanguage->key,
        ];
    }
}
