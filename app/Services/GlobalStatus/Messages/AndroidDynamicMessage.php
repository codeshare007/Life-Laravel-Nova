<?php

namespace App\Services\GlobalStatus\Messages;

use App\Enums\StatusType;
use Unisharp\Setting\Setting;
use Illuminate\Support\Carbon;
use App\Services\GlobalStatus\Message;

class AndroidDynamicMessage extends Message
{
    /** @var Setting */
    protected $settings;

    public function __construct(Setting $settings)
    {
        $this->settings = $settings;
    }

    protected function type(): StatusType
    {
        return StatusType::getInstance($this->settings->get('android_status_type', StatusType::Ok));
    }
    
    protected function title(): string
    {
        return $this->settings->get('android_status_title', '');
    }
    
    protected function message(): string
    {
        return $this->settings->get('android_status_message', '');
    }
    
    protected function buttonText(): string
    {
        return $this->settings->get('android_status_button_text', '');
    }
    
    protected function buttonUrl(): string
    {
        return $this->settings->get('android_status_button_url', '');
    }
    
    protected function updatedAt(): Carbon
    {
        return Carbon::parse($this->settings->get('android_status_updated_at', Carbon::createFromTimestamp(0)));
    }
}
