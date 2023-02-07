<?php

namespace App\Http\Controllers\Api\v3_0\Subscription;

use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Subscriptions\Subscription;
use App\Http\Resources\v3_0\SubscriptionResource;
use App\Http\Requests\v3_0\Subscription\VerifyReceiptRequest;

class VerifyReceiptController extends Controller
{
    public function __invoke(VerifyReceiptRequest $request)
    {
        Log::info("Checking receipt: $request->receipt");

        $subscription = Subscription::get($request->receipt, $request->platform);

        $user = User::find($request->user_id);

        if ($user) {
            if ($user->bypass_subscription_receipt_validation) {
                if ($user->subscription_expires_at->isFuture()) {
                    $subscription->applyBypass($user->subscription_expires_at);
                } else {
                    $user->update([
                        'bypass_subscription_receipt_validation' => false,
                    ]);
                }
            }

            $user->updateSubscription($subscription);

            // This temporarily fixes an issue with specific users who use non gregorian calendars not being able to log in
            if ($user->email === 'jkp.wealth@gmail.com') {
                $subscription->expiration = Carbon::now()->addYears(1000);
            }

            Log::info("Subscription updated for $user->email with the expiration $subscription->expiration and type {$subscription->type->key}");
        }

        return SubscriptionResource::make($subscription);
    }
}
