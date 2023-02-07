<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Http\Resources\Json\JsonResource;

class InAppNotificationResource extends JsonResource
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
            'resource_type' => 'InAppNotification',
            'notification_type' => class_basename($this->type),
            'id' => $this->id,
            'data' => $this->data,
            'created_at' => $this->created_at->toDateTimeString(),
            'read_at' => $this->read_at ? $this->read_at->toDateTimeString() : null,
            'expires_at' => $this->expires_at,
        ];
    }
}
