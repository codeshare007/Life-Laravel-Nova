<?php

namespace Tests\Feature\Api\LegacyAuth;

use App\User;
use App\Avatar;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SignUpTest extends TestCase
{
    use RefreshDatabase;

    protected $avatar;

    public function setUp()
    {
        parent::setUp();
        \Config::set('api.version', '1_2');

        $this->avatar = factory(Avatar::class)->create();
    }

    public function test_user_can_sign_up()
    {
        $userAttributes = [
            'email' => 'someuser@example.com',
            'password' => 'secret',
            'name' => 'oilqueen101',
            'avatar_id' => $this->avatar->id,
        ];

        $response = $this->json('POST', '/auth/signup', $userAttributes);

        $response->assertStatus(201);

        $this->assertEquals(1, User::count());

        $user = User::first();
        $this->assertEquals($user->email, $userAttributes['email']);
        $this->assertTrue(Hash::check($userAttributes['password'], $user->password));
        $this->assertEquals($user->name, $userAttributes['name']);
        $this->assertEquals($user->avatar_id, $userAttributes['avatar_id']);
    }

    public function test_user_is_assigned_a_name_based_on_email_address_if_none_is_supplied_at_signup()
    {
        $userAttributes = [
            'email' => 'someuser@example.com',
            'password' => 'secret',
        ];

        $response = $this->json('POST', '/auth/signup', $userAttributes);

        $this->assertEquals(User::first()->name, 'someuser');
    }

    public function test_user_is_assigned_a_random_avatar_if_none_is_supplied_at_signup()
    {
        factory(Avatar::class, 2)->create();

        $userAttributes = [
            'email' => 'someuser@example.com',
            'password' => 'secret',
        ];

        $response = $this->json('POST', '/auth/signup', $userAttributes);

        $this->assertContains(User::first()->avatar_id, Avatar::all('id')->pluck('id'));
    }

    public function test_user_cannot_sign_up_with_an_email_which_is_already_in_use()
    {
        factory(User::class)->create([
            'email' => 'someuser@example.com',
        ]);

        $userAttributes = [
            'email' => 'someuser@example.com',
            'password' => 'secret',
        ];

        $response = $this->json('POST', '/auth/signup', $userAttributes);
        $response->assertJsonValidationErrors('email');
    }

    public function test_user_cannot_sign_up_with_a_name_which_is_already_in_use()
    {
        factory(User::class)->create([
            'name' => 'oilqueen101',
        ]);

        $userAttributes = [
            'email' => 'someuser@example.com',
            'password' => 'secret',
            'name' => 'oilqueen101',
        ];

        $response = $this->json('POST', '/auth/signup', $userAttributes);
        $response->assertJsonValidationErrors('name');
    }
}
