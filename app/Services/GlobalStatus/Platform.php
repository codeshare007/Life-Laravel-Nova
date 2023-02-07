<?php

namespace App\Services\GlobalStatus;

use App\Enums\UserLanguage;
use App\Services\GlobalStatus\Contracts\MessageContract;

abstract class Platform
{
    protected $appVersion;

    /** @var UserLanguage */
    protected $language;
    
    public function __construct($appVersion, UserLanguage $language)
    {
        $this->appVersion = $appVersion;
        $this->language = $language;
    }

    abstract protected function message(): MessageContract;
}
