<?php

namespace Tests\Feature\Api\v3_0;

use App\User;
use Tests\TestCase;
use App\Enums\Platform;
use Illuminate\Support\Carbon;
use App\Enums\SubscriptionType;
use App\Services\Subscriptions\iOS\iOSPlatform;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerifyReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_receipt_returns_subscription_details()
    {
        $this->verifyReceipt()
            ->assertSuccessful()
            ->assertJsonFragment([
                'platform' => Platform::iOS,
                'type' => SubscriptionType::TheEssentialLifeMembership12Month()->key,
                'is_active' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    'expires_at',
                ],
            ]);
    }

    public function test_verify_receipt_returns_subscription_details_for_invalid_receipt()
    {
        $this->verifyReceipt([
            'receipt' => 'invalid-receipt'
        ])->assertSuccessful()
        ->assertJsonFragment([
            'platform' => Platform::iOS,
            'type' => SubscriptionType::None()->key,
            'is_active' => false,
            'expires_at' => Carbon::createFromTimestamp(0)->addDay()->toDateTimeString(),
        ]);
    }

    public function test_cannot_verify_receipt_without_receipt()
    {
        $this->verifyReceipt([
            'receipt' => null,
        ])->assertJsonValidationErrors('receipt');
    }

    public function test_cannot_verify_receipt_without_platform()
    {
        $this->verifyReceipt([
            'platform' => null,
        ])->assertJsonValidationErrors('platform');
    }

    public function test_when_user_id_is_provided_subscription_details_are_recorded_against_the_user()
    {
        $user = factory(User::class)->create([
            'subscription_type' => SubscriptionType::None(),
            'subscription_expires_at' => null,
        ]);

        $this->verifyReceipt([
            'user_id' => $user->id,
        ])->assertJsonFragment([
            'type' => SubscriptionType::TheEssentialLifeMembership12Month()->key,
        ]);

        $user->refresh();

        $this->assertEquals(SubscriptionType::TheEssentialLifeMembership12Month(), $user->subscription_type);
        $this->assertNotNull($user->subscription_expires_at);
        $this->assertEquals(Platform::iOS(), $user->platform);
    }

    // Bypass: On
    // Expiry date: Future
    // Receipt: Invalid
    public function test_user_with_bypass_subscription_receipt_returns_valid_subscription()
    {
        $subscriptionExpiryDate = now()->addDay();

        $user = factory(User::class)->create([
            'subscription_type' => SubscriptionType::None(),
            'subscription_expires_at' => $subscriptionExpiryDate,
            'bypass_subscription_receipt_validation' => true,
        ]);

        $this->verifyReceipt([
            'receipt' => 'invalid-receipt',
            'platform' => Platform::iOS,
            'user_id' => $user->id,
        ])->assertJsonFragment([
            'type' => SubscriptionType::TheEssentialLifeMembership12Month()->key,
            'expires_at' => $subscriptionExpiryDate->toDateTimeString(),
            'is_active' => true,
        ]);
    }

    // Bypass: On
    // Expiry date: Past
    // Receipt: Invalid
    public function test_user_with_bypass_subscription_receipt_returns_invalid_subscription_when_expires_at_date_is_in_the_past()
    {
        $subscriptionExpiryDate = now()->subDay();

        $user = factory(User::class)->create([
            'subscription_type' => SubscriptionType::None(),
            'subscription_expires_at' => $subscriptionExpiryDate,
            'bypass_subscription_receipt_validation' => true,
        ]);

        $this->verifyReceipt([
            'receipt' => 'invalid-receipt',
            'platform' => Platform::iOS,
            'user_id' => $user->id,
        ])->assertJsonFragment([
            'type' => SubscriptionType::None()->key,
            'is_active' => false,
        ]);
    }

    // Bypass: On
    // Expiry date: Past
    // Receipt: Valid
    public function test_when_a_user_with_bypass_enabled_where_the_subscription_expiry_date_is_in_the_past_the_response_for_a_valid_receipt_returns_a_valid_subscription_and_bypass_is_disabled()
    {
        $subscriptionExpiryDate = now()->subDay();

        $user = factory(User::class)->create([
            'subscription_type' => SubscriptionType::None(),
            'subscription_expires_at' => $subscriptionExpiryDate,
            'bypass_subscription_receipt_validation' => true,
        ]);

        $this->verifyReceipt([
            'receipt' => iOSPlatform::BYPASS_TOKEN,
            'platform' => Platform::iOS,
            'user_id' => $user->id,
        ])->assertJsonFragment([
            'type' => SubscriptionType::TheEssentialLifeMembership12Month()->key,
            'is_active' => true,
        ]);

        $this->assertFalse($user->fresh()->bypass_subscription_receipt_validation);
    }

    protected function verifyReceipt(array $overrides = []): TestResponse
    {
        $attributes = array_merge([
            'receipt' => iOSPlatform::BYPASS_TOKEN,
            'platform' => Platform::iOS,
            'user_id' => null,
        ], $overrides);

        return $this->json('post', '/en/v3.0/verify-receipt', $attributes, [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjA1NDdlZjVlZTcyNjMwZDI1NDRkZjg3YTFlNmY1MDRiYzQwZmNkMzEzNzI4NTgwODQ3ZDI3MDdlMmU4YmI3YjI5ZGM0ODhlNjJkMmIwMWI5In0.eyJhdWQiOiI0IiwianRpIjoiMDU0N2VmNWVlNzI2MzBkMjU0NGRmODdhMWU2ZjUwNGJjNDBmY2QzMTM3Mjg1ODA4NDdkMjcwN2UyZThiYjdiMjlkYzQ4OGU2MmQyYjAxYjkiLCJpYXQiOjE1NDIyOTgzNTQsIm5iZiI6MTU0MjI5ODM1NCwiZXhwIjoxNTczODM0MzU0LCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.f4N3HRRIAEr3Eef0XrZUV-eZF5fVSpJqnTAjM2vE9fDgs8r9yYxQvYIPL--yX9z20PR1AqUUetCwNLSICMuFJc_s8o5Flc6OVr3apAlq7o5Pd3Z0sIRNpsSNoYNBz-1b3v5YmqJkkZxpbcxUT7JnHnXuK5uv-e0H434Sb8xmvYdxCIVrvPzQmEtFAYMwc1KM7Ce0XMBOWY0cvdyrKN6KldgpbswwH4LgX4a9YcaehCONMiMF70tq-f6PDI3UbgBHI4iJZPGMic0Gew-Fz95K5byrsJzZjmzOugfmQPuwejaNVuEbmeNLo1aSZWJcAzvmNhh1hyTfS5_qqYx3cA7uSCp3NZEjIPWB9bfegWsFV1wvQLeOiTSfZO0BXCDsO8pO56A9iMkV3weQi4JGiywXn-gWWUlYXUJCFZPXwpAFCdjEfPguEFW3-_3EoN552BJhr1spWUY9t_GS4ZHwPrSrOZuVpduvfkDuZGEyZz8GX7hRx56bTmBiv-pIEdU46JFHcmnin9U_JVZd-Y3YUxh9hmX8F_kC71DN1PNbKuTgXcIb9Vhs24c50y1m6MoUzgHFh6bIzgfsckcb9n4Ub3RFUEsmilmprHsMq_oUpukpb0ZtSRfo3OnrMp3y-DTwGBOgJGBv_Uq7rZ1hfAz2KgJ2q1g7RgLd2YxsqYjC0sqjnmQ'
        ]);
    }
}
