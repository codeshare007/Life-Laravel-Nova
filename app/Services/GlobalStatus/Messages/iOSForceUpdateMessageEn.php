<?php

namespace App\Services\GlobalStatus\Messages;

use App\Enums\StatusType;
use Illuminate\Support\Carbon;
use App\Services\GlobalStatus\Message;

class iOSForceUpdateMessageEn extends Message
{
    protected function type(): StatusType
    {
        return StatusType::Error();
    }
    
    protected function title(): string
    {
        return 'Update Available';
    }
    
    protected function message(): string
    {
        return 'Woohoo! The latest version of the Essential Life app is available. 

Please go to the iOS App Store and download the latest version.';
    }
    
    protected function buttonText(): string
    {
        return 'Update';
    }
    
    protected function buttonUrl(): string
    {
        return 'https://itunes.apple.com/app/id1434661865';
    }
    
    protected function updatedAt(): Carbon
    {
        return now();
    }
}
