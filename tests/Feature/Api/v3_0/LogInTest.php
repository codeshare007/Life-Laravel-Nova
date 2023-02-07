<?php

namespace Tests\Feature\Api\v3_0;

use App\User;
use Tests\TestCase;
use App\Enums\UserLanguage;
use Tests\Traits\RefreshAllDatabases;
use Illuminate\Support\Facades\Artisan;
use App\Services\LanguageDatabaseService;
use Illuminate\Foundation\Testing\TestResponse;

class LogInTest extends TestCase
{
    use RefreshAllDatabases;

    /** @var User */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('passport:install');

        $this->user = factory(User::class)->create();
    }

    public function test_user_can_log_in()
    {
        $this->login()->assertSuccessful();
    }

    public function test_cannot_log_in_with_invalid_credentials()
    {
        $this->login([
            'email' => 'user.who.does@not.exist',
        ])->assertStatus(401);
    }

    public function test_login_response_contains_expected_json()
    {
        $this->login()->assertJsonFragment([
            'id' => $this->user->id,
            'name' => $this->user->name,
            'bio' => $this->user->bio,
            'avatar_url' => $this->user->avatarUrl(),
            'subscription_type' => $this->user->subscription_type->key,
            'language' => 'en',
            'token_type' => 'Bearer',
        ])->assertJsonStructure([
            'data' => [
                'access_token',
                'session_expires_at',
                'subscription_expires_at',
                'content',
            ]
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

    public function test_can_login_as_a_user_on_a_different_language_db()
    {
        // Create user in a the Spanish DB then swap back to the English DB
        $dbService = new LanguageDatabaseService();
        $dbService->setLanguage(UserLanguage::Spanish());
        Artisan::call('passport:install');
        $user = factory(User::class)->create();
        $dbService->reset();

        $this->login([
            'email' => $user->email,
        ])->assertOk()->assertJsonFragment([
            'language' => 'es',
        ]);
    }

    protected function login(array $overrides = []): TestResponse
    {
        $attributes = array_merge([
            'email' => $this->user->email,
            'password' => 'secret',
        ], $overrides);

        return $this->json('post', '/en/v3.0/auth/login', $attributes, [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjA1NDdlZjVlZTcyNjMwZDI1NDRkZjg3YTFlNmY1MDRiYzQwZmNkMzEzNzI4NTgwODQ3ZDI3MDdlMmU4YmI3YjI5ZGM0ODhlNjJkMmIwMWI5In0.eyJhdWQiOiI0IiwianRpIjoiMDU0N2VmNWVlNzI2MzBkMjU0NGRmODdhMWU2ZjUwNGJjNDBmY2QzMTM3Mjg1ODA4NDdkMjcwN2UyZThiYjdiMjlkYzQ4OGU2MmQyYjAxYjkiLCJpYXQiOjE1NDIyOTgzNTQsIm5iZiI6MTU0MjI5ODM1NCwiZXhwIjoxNTczODM0MzU0LCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.f4N3HRRIAEr3Eef0XrZUV-eZF5fVSpJqnTAjM2vE9fDgs8r9yYxQvYIPL--yX9z20PR1AqUUetCwNLSICMuFJc_s8o5Flc6OVr3apAlq7o5Pd3Z0sIRNpsSNoYNBz-1b3v5YmqJkkZxpbcxUT7JnHnXuK5uv-e0H434Sb8xmvYdxCIVrvPzQmEtFAYMwc1KM7Ce0XMBOWY0cvdyrKN6KldgpbswwH4LgX4a9YcaehCONMiMF70tq-f6PDI3UbgBHI4iJZPGMic0Gew-Fz95K5byrsJzZjmzOugfmQPuwejaNVuEbmeNLo1aSZWJcAzvmNhh1hyTfS5_qqYx3cA7uSCp3NZEjIPWB9bfegWsFV1wvQLeOiTSfZO0BXCDsO8pO56A9iMkV3weQi4JGiywXn-gWWUlYXUJCFZPXwpAFCdjEfPguEFW3-_3EoN552BJhr1spWUY9t_GS4ZHwPrSrOZuVpduvfkDuZGEyZz8GX7hRx56bTmBiv-pIEdU46JFHcmnin9U_JVZd-Y3YUxh9hmX8F_kC71DN1PNbKuTgXcIb9Vhs24c50y1m6MoUzgHFh6bIzgfsckcb9n4Ub3RFUEsmilmprHsMq_oUpukpb0ZtSRfo3OnrMp3y-DTwGBOgJGBv_Uq7rZ1hfAz2KgJ2q1g7RgLd2YxsqYjC0sqjnmQ'
        ]);
    }
}
