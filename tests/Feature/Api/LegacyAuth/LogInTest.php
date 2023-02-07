<?php

namespace Tests\Feature\Api\LegacyAuth;

use App\User;
use Tests\TestCase;
use App\Enums\Platform;
use App\Enums\UserLanguage;
use Tests\Traits\RefreshAllDatabases;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestResponse;
use App\Exceptions\InactiveSubscriptionException;
use App\Services\LanguageDatabaseService;

class LogInTest extends TestCase
{
    use RefreshAllDatabases;

    /** @var User */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('passport:install');

        $this->user = factory(User::class)->create([
            'bypass_subscription_receipt_validation' => true,
        ]);
    }

    public function test_ios_user_can_log_in()
    {
        $this->login([
            'platform' => Platform::iOS,
        ])->assertSuccessful();
    }

    public function test_android_user_can_log_in()
    {
        $this->login([
            'platform' => Platform::Android,
        ])->assertSuccessful();
    }

    public function test_cannot_log_in_with_invalid_credentials()
    {
        $this->withoutExceptionHandling();
        $this->login([
            'email' => 'user.who.does@not.exist',
        ])->assertStatus(401);
    }

    public function test_user_with_invalid_receipt_cannot_log_in()
    {
        $this->login([
            'email' => factory(User::class)->create()->email,
            'receipt' => 'invalid',
        ])->assertStatus(402);
    }

    public function test_exception_is_thrown_when_user_receipt_validation_fails()
    {
        $this->withoutExceptionHandling();
        $this->expectException(InactiveSubscriptionException::class);

        $this->login([
            'email' => factory(User::class)->create()->email,
            'receipt' => 'invalid',
        ]);
    }

    public function test_login_response_contains_expected_json()
    {
        $this->login()->assertJson([
            'user_id' => $this->user->id,
            'language' => 'en',
            'token_type' => 'Bearer',
            'subscription_type' => $this->user->subscription_type->key,
        ])->assertJsonStructure([
            'access_token',
            'session_expires_at',
            'subscription_expires_at',
        ]);
    }

    public function test_validation_fails_when_email_is_not_supplied()
    {
        $this->login([
            'email' => null,
        ])->assertJsonValidationErrors([
            'email'
        ]);
    }

    public function test_validation_fails_when_password_is_not_supplied()
    {
        $this->login([
            'password' => null,
        ])->assertJsonValidationErrors([
            'password'
        ]);
    }

    public function test_validation_fails_when_remember_me_is_not_supplied()
    {
        $this->login([
            'remember_me' => null,
        ])->assertJsonValidationErrors([
            'remember_me'
        ]);
    }

    public function test_validation_fails_when_receipt_is_not_supplied()
    {
        $this->login([
            'receipt' => null,
        ])->assertJsonValidationErrors([
            'receipt'
        ]);
    }

    public function test_platform_is_optional()
    {
        $this->login([
            'platform' => null,
        ])->assertOk();
    }

    public function test_user_with_apple_test_email_skips_receipt_validation()
    {
        $appleTestUser = factory(User::class)->create([
            'email' => 'someone@apple-wqa-dev.test',
        ]);

        $this->login([
            'email' => $appleTestUser->email,
            'receipt' => null,
        ])->assertOk();
    }

    public function test_can_login_as_a_user_on_a_different_language_db()
    {
        $dbService = new LanguageDatabaseService();

        $dbService->setLanguage(UserLanguage::Spanish());

        Artisan::call('passport:install');

        $user = factory(User::class)->create([
            'bypass_subscription_receipt_validation' => true,
        ]);

        $dbService->reset();

        $this->login([
            'email' => $user->email,
        ])->assertOk()->assertJson([
            'language' => 'es',
        ]);
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
