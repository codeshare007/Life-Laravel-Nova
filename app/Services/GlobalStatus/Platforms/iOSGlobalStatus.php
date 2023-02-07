<?php

namespace App\Services\GlobalStatus\Platforms;

use App\Enums\UserLanguage;
use Illuminate\Support\Facades\App;
use App\Services\GlobalStatus\Platform;
use App\Services\GlobalStatus\Contracts\MessageContract;
use App\Services\GlobalStatus\Messages\iOSDynamicMessage;
use App\Services\GlobalStatus\Messages\iOSForceUpdateMessageEn;
use App\Services\GlobalStatus\Messages\iOSForceUpdateMessageEs;
use App\Services\GlobalStatus\Messages\iOSForceUpdateMessageJa;
use App\Services\GlobalStatus\Messages\iOSForceUpdateMessagePt;

class iOSGlobalStatus extends Platform
{
    protected $forceUpdatePreVersion = 4.3;

    public function message(): MessageContract
    {
        if (! App::environment('staging')) {
            if ($this->olderThanForceUpdateVersion() || $this->onUnstableVersion()) {
                return $this->forceUpdateMessageByLanguage();
            }
        }

        return resolve(iOSDynamicMessage::class);
    }

    protected function olderThanForceUpdateVersion()
    {
        return floatval($this->appVersion) < $this->forceUpdatePreVersion;
    }

    protected function onUnstableVersion()
    {
        $version = $this->appVersion;

        return in_array($version, [
            '4.3.1',
            '4.3.2',
            '4.3.3',
            '4.3.4',
            '4.3.5',
            '4.3.6',
            '4.3.7',
            '4.3.8',
            '4.3.9',
            '4.3.10',
            '4.3.11',
            '4.3.12',
            '4.3.13',
        ]);
    }

    protected function forceUpdateMessageByLanguage(): MessageContract
    {
        if ($this->language->is(UserLanguage::Spanish)) {
            return resolve(iOSForceUpdateMessageEs::class);
        }

        if ($this->language->is(UserLanguage::Portugese)) {
            return resolve(iOSForceUpdateMessagePt::class);
        }

        if ($this->language->is(UserLanguage::Japanese)) {
            return resolve(iOSForceUpdateMessageJa::class);
        }

        return resolve(iOSForceUpdateMessageEn::class);
    }
}
