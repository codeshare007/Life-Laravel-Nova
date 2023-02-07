<?php

namespace App\Http\Resources\v3_0;

use App\User;
use Illuminate\Support\Carbon;
use App\Enums\SubscriptionType;
use Laravel\Passport\PersonalAccessTokenResult;

/**
 * @OA\Schema(
 *     schema="UserSensitiveResource3.0",
 *     type="object",
 *     title="User Sensitive Resource V3.0",
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
 *     ),
 *     @OA\Property(
 *          property="access_token",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="session_expires_at",
 *          type="date-time"
 *     )
 *)
 */
class UserSensitiveResource extends BaseResource
{
    private $personalAccessToken;

    public function __construct(User $user, PersonalAccessTokenResult $personalAccessToken = null)
    {
        parent::__construct($user);

        $this->personalAccessToken = $personalAccessToken;
    }

    public function toArray($request)
    {
        $array = [
            'resource_type' => 'UserSensitive',
            'id' => $this->id,
            'name' => $this->name,
            'region_id' => $this->region_id ?? 0, // default region id to 0 as front end can't handle nullable values in select
            'bio' => $this->bio,
            'language' => $this->language->value,
            'avatar_url' => $this->avatarUrl(),
            'subscription_type' => $this->getSubscriptionType(),
            'subscription_expires_at' => $this->subscription_expires_at ? $this->subscription_expires_at->toDateTimeString() : null,
            'favourites' => array_filter($this->pluck($this->whenLoaded('favourites'), 'favouriteable.uuid')),
            'content' => UserGeneratedContentResource::collection($this->content),
        ];

        if ($this->personalAccessToken) {
            $array = array_merge($array, [
                'token_type' => 'Bearer',
                'access_token' => $this->personalAccessToken->accessToken,
                'session_expires_at' => Carbon::parse($this->personalAccessToken->token->expires_at)->toDateTimeString(),
            ]);
        }

        // In order for Apple to test the app, they need to have a subscription without sending a receipt at login (and therefore having
        // subscription on the user model). We send fake data back here, from the currentUser endpoint.
        if (str_contains($this->email, '@apple-wqa-dev.test')) {
            $array = array_merge($array, [
                'subscription_type' => SubscriptionType::TheEssentialLifeMembership12Month()->key,
                'subscription_expires_at' => Carbon::now()->addYear()->toDateTimeString(),
            ]);
        }

        // This temporarily fixes an issue with specific users who use non gregorian calendars not being able to log in
        if ($this->email === 'jkp.wealth@gmail.com') {
            $array = array_merge($array, [
                'subscription_type' => SubscriptionType::TheEssentialLifeMembership12Month()->key,
                'subscription_expires_at' => Carbon::now()->addYears(1000)->toDateTimeString(),
            ]);
        }

        return array_filter($array);
    }

    public function getSubscriptionType()
    {
        // The App doesn't expect the TheEssentialLifeMembershipTrial type. It will break if we return that value.
        // For now, we'll just fake it and return what would have been returned before the change.
        if (
            (! $this->subscription_type && $this->isDevTest()) ||
            ($this->subscription_type && $this->subscription_type->is(SubscriptionType::TheEssentialLifeMembershipTrial))
        ) {
            return SubscriptionType::TheEssentialLifeMembership12Month()->key;
        }

        return $this->subscription_type->key ?? null;
    }
}
