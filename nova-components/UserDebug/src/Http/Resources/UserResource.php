<?php

namespace Wqa\UserDebug\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'subscription_type' => $this->subscription_type->description ?? '-',
            'platform' => $this->platform->description ?? '-',
            'subscription_expires_at' => $this->subscription_expires_at->toDateTimeString(),
        ]);
    }
}
