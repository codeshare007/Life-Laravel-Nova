<?php

namespace App\Http\Resources\v2_1;

use Illuminate\Support\Carbon;
use App\Enums\SubscriptionType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserSensitiveResource2.1",
 *     type="object",
 *     title="User Sensitive Resource V2.1",
 *     @OA\Property(
 *          property="resource_type",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="name",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="region_id",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="language",
 *          type="string"
 *     ),
  *     @OA\Property(
 *          property="bio",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="avatar_url",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="subscription_type",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="subscription_expires_at",
 *          type="date-time"
 *     ),
 *     @OA\Property(
 *          property="favourites",
 *          type="array",
 *          @OA\Items()
 *     ),
 *     @OA\Property(
 *          property="content",
 *          type="array",
 *          @OA\Items()
 *     ),
 *     @OA\Property(
 *          property="token_type",
 *          type="string"
 *     )
 *)
 */
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
            // default region id to 0 as front end can't handle
            // nullable values in select
            'region_id' => $this->region_id ?? 0,
            'bio' => $this->bio,
            'avatar_url' => $this->avatarUrl(),
            'subscription_type' => $this->getSubscriptionType(),
            'subscription_expires_at' => $this->subscription_expires_at ? $this->subscription_expires_at->toDateTimeString() : null,
            'favourites' => $this->favouriteWithData ? FavouriteResource::collection($this->favouriteWithData) : null,
            'content' => UserGeneratedContentResource::collection($this->content)
        ];


        // In order for Apple to test the app, they need to have a subscription without sending a receipt at login (and therefore having
        // subscription on the user model). We send fake data back here, from the currentUser endpoint.
        if (str_contains($this->email, '@apple-wqa-dev.test')) {
            $array = array_merge($array, [
                'subscription_type' => SubscriptionType::TheEssentialLifeMembership12Month()->key,
                'subscription_expires_at' => Carbon::now()->addYear()->toDateTimeString(),
            ]);
        }

        return array_filter($array);
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
