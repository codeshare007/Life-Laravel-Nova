<?php

namespace Tests\Feature\Api\v3_0;

use App\User;
use App\Avatar;
use Tests\TestCase;
use App\Enums\Platform;
use Illuminate\Http\Response;
use App\Services\Subscriptions\iOS\iOSPlatform;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SignUpTest extends TestCase
{
    use RefreshDatabase;

    protected $avatar;

    public function setUp()
    {
        parent::setUp();
        \Config::set('api.version', '3_0');
    }

    public function test_user_can_sign_up()
    {
        $this->signUp();

        $this->assertEquals(1, User::count());
        $this->assertFalse(User::first()->bypass_subscription_receipt_validation);
    }

    public function test_user_cannot_sign_up_without_receipt()
    {
        $this->signUp([
            'receipt' => null,
        ])->assertJsonValidationErrors('receipt');
    }

    public function test_user_cannot_sign_up_without_platform()
    {
        $this->signUp([
            'platform' => null,
        ])->assertJsonValidationErrors('platform');
    }

    public function test_user_cannot_sign_up_with_invalid_platform()
    {
        $this->signUp([
            'platform' => 'invalid',
        ])->assertJsonValidationErrors('platform');
    }

    public function test_user_cannot_sign_up_without_valid_subscription()
    {
        $this->signUp([
            'receipt' => 'invalid-receipt',
        ])->assertStatus(Response::HTTP_PAYMENT_REQUIRED);
    }

    public function test_user_is_assigned_a_name_based_on_email_address_if_none_is_supplied_at_signup()
    {
        $this->signUp([
            'name' => null,
        ])->assertStatus(201);

        $this->assertEquals(User::first()->name, 'someuser');
    }

    public function test_user_is_assigned_a_random_avatar_if_none_is_supplied_at_signup()
    {
        factory(Avatar::class, 2)->create();

        $this->signUp([
            'avatar' => null,
        ])->assertStatus(201);

        $this->assertContains(User::first()->avatar_id, Avatar::all('id')->pluck('id'));
    }

    public function test_user_cannot_sign_up_with_an_email_which_is_already_in_use()
    {
        factory(User::class)->create([
            'email' => 'someuser@example.com',
        ]);

        $this->signUp([
            'email' => 'someuser@example.com',
        ])->assertJsonValidationErrors('email');
    }

    public function test_user_cannot_sign_up_with_a_name_which_is_already_in_use()
    {
        factory(User::class)->create([
            'name' => 'oilqueen101',
        ]);

        $this->signUp([
            'name' => 'oilqueen101',
        ])->assertJsonValidationErrors('name');
    }

    public function test_user_with_dev_email_does_not_need_valid_receipt()
    {
        $this->signUp([
            'email' => 'test@apple-wqa-dev.test',
            'receipt' => null,
        ])->assertStatus(201);

        $this->assertEquals(1, User::count());
    }

    public function test_user_signed_up_with_dev_email_has_receipt_bypass_enabled()
    {
        $this->signUp([
            'email' => 'test@apple-wqa-dev.test',
        ]);

        $this->assertEquals(1, User::count());
        $this->assertTrue(User::first()->bypass_subscription_receipt_validation);
    }

    protected function signUp(array $overrides = []): TestResponse
    {
        $avatar = factory(Avatar::class)->create();

        $attributes = array_merge([
            'email' => 'someuser@example.com',
            'password' => 'secret',
            'name' => 'oilqueen101',
            'avatar_id' => $avatar->id,
            'receipt' => iOSPlatform::BYPASS_TOKEN,
            'platform' => Platform::iOS,
        ], $overrides);

        return $this->json('post', '/en/v3.0/auth/signup', $attributes, [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjA1NDdlZjVlZTcyNjMwZDI1NDRkZjg3YTFlNmY1MDRiYzQwZmNkMzEzNzI4NTgwODQ3ZDI3MDdlMmU4YmI3YjI5ZGM0ODhlNjJkMmIwMWI5In0.eyJhdWQiOiI0IiwianRpIjoiMDU0N2VmNWVlNzI2MzBkMjU0NGRmODdhMWU2ZjUwNGJjNDBmY2QzMTM3Mjg1ODA4NDdkMjcwN2UyZThiYjdiMjlkYzQ4OGU2MmQyYjAxYjkiLCJpYXQiOjE1NDIyOTgzNTQsIm5iZiI6MTU0MjI5ODM1NCwiZXhwIjoxNTczODM0MzU0LCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.f4N3HRRIAEr3Eef0XrZUV-eZF5fVSpJqnTAjM2vE9fDgs8r9yYxQvYIPL--yX9z20PR1AqUUetCwNLSICMuFJc_s8o5Flc6OVr3apAlq7o5Pd3Z0sIRNpsSNoYNBz-1b3v5YmqJkkZxpbcxUT7JnHnXuK5uv-e0H434Sb8xmvYdxCIVrvPzQmEtFAYMwc1KM7Ce0XMBOWY0cvdyrKN6KldgpbswwH4LgX4a9YcaehCONMiMF70tq-f6PDI3UbgBHI4iJZPGMic0Gew-Fz95K5byrsJzZjmzOugfmQPuwejaNVuEbmeNLo1aSZWJcAzvmNhh1hyTfS5_qqYx3cA7uSCp3NZEjIPWB9bfegWsFV1wvQLeOiTSfZO0BXCDsO8pO56A9iMkV3weQi4JGiywXn-gWWUlYXUJCFZPXwpAFCdjEfPguEFW3-_3EoN552BJhr1spWUY9t_GS4ZHwPrSrOZuVpduvfkDuZGEyZz8GX7hRx56bTmBiv-pIEdU46JFHcmnin9U_JVZd-Y3YUxh9hmX8F_kC71DN1PNbKuTgXcIb9Vhs24c50y1m6MoUzgHFh6bIzgfsckcb9n4Ub3RFUEsmilmprHsMq_oUpukpb0ZtSRfo3OnrMp3y-DTwGBOgJGBv_Uq7rZ1hfAz2KgJ2q1g7RgLd2YxsqYjC0sqjnmQ'
        ]);
    }
}
