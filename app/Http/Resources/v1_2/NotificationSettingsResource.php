<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'resource_type' => 'NotificationSettings',
            'enabled' => $this->enabled,
            'notify_for_favourites' => $this->notify_for_favourites,
            'frequency' => $this->frequency,
        ];
    }
}
