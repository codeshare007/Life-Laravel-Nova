<?php

namespace App\Http\Resources\v1_2;

use Illuminate\Support\Carbon;
use App\Enums\SubscriptionType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSensitiveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'resource_type' => 'UserSensitive',
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'avatar_url' => $this->avatarUrl(),
            'subscription_type' => $this->getSubscriptionType(),
            'subscription_expires_at' => $this->subscription_expires_at ? $this->subscription_expires_at->toDateTimeString() : null,
        ];


        // In order for Apple to test the app, they need to have a subscription without sending a receipt at login (and therefore having
        // subscription on the user model). We send fake data back here, from the currentUser endpoint.
        if (str_contains($this->email, '@apple-wqa-dev.test')) {
            $array = array_merge($array, [
                'subscription_type' => SubscriptionType::TheEssentialLifeMembership12Month()->key,
                'subscription_expires_at' => Carbon::now()->addYear()->toDateTimeString(),
            ]);
        }

        return $array;
    }

    public function getSubscriptionType()
    {
        // The App doesn't expect the TheEssentialLifeMembershipTrial type. It will break if we return that value.
        // For now, we'll just fake it and return what would have been returned before the change.
        if ($this->subscription_type->is(SubscriptionType::TheEssentialLifeMembershipTrial)) {
            return SubscriptionType::TheEssentialLifeMembership12Month()->key;
        }

        return $this->subscription_type->key;
    }
}
