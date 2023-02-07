<?php

namespace Tests\Feature\Api\LegacyAuth;

use App\User;
use Tests\TestCase;
use App\Enums\Platform;
use Tests\Traits\RefreshAllDatabases;
use Illuminate\Support\Facades\Artisan;
use App\Services\Subscriptions\BasePlatform;
use Illuminate\Foundation\Testing\TestResponse;
use App\Exceptions\InactiveSubscriptionException;

class SubscriptionBypassTest extends TestCase
{
    use RefreshAllDatabases;

    /** @var User */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('passport:install');
    }

    public function test_a_user_with_bypass_enabled_where_the_subscription_expiry_date_is_in_the_future_can_log_in()
    {
        $expiresAtDate = now()->addDay();

        $this->user = factory(User::class)->create([
            'bypass_subscription_receipt_validation' => true,
            'subscription_expires_at' => $expiresAtDate,
        ]);

        $this->login()->assertSuccessful()->assertJson([
            'subscription_expires_at' => $expiresAtDate,
        ]);
    }

    public function test_a_user_with_bypass_enabled_where_the_subscription_expiry_date_is_in_the_past_can_not_log_in()
    {
        $this->withoutExceptionHandling();
        $this->expectException(InactiveSubscriptionException::class);

        $this->user = factory(User::class)->create([
            'bypass_subscription_receipt_validation' => true,
            'subscription_expires_at' => now()->subDay(),
        ]);

        $this->login();
    }

    public function test_a_user_with_bypass_enabled_where_the_subscription_expiry_date_is_in_the_past_can_log_in_with_valid_receipt_and_bypass_is_disabled()
    {
        $expiresAtDate = now()->subDay();

        $this->user = factory(User::class)->create([
            'bypass_subscription_receipt_validation' => true,
            'subscription_expires_at' => $expiresAtDate,
        ]);

        $this->login([
            'receipt' => BasePlatform::BYPASS_TOKEN,
        ])->assertSuccessful();

        $this->assertFalse($this->user->fresh()->bypass_subscription_receipt_validation);
    }

    protected function login(array $overrides = []): TestResponse
    {
        $attributes = array_merge([
            'email' => $this->user->email,
            'password' => 'secret',
            'remember_me' => 'false',
            'platform' => Platform::iOS,
            'receipt' => 'dummy-receipt',
        ], $overrides);

        return $this->json('POST', '/auth/login', $attributes);
    }
}
