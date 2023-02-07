<?php

namespace App\Http\Resources\v3_0;

class SubscriptionResource extends BaseResource
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
            'platform' => $this->platform->value,
            'type' => $this->type->key,
            'expires_at' => $this->expiration->toDateTimeString(),
            'is_active' => $this->isActive,
        ];
    }
}
