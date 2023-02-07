<?php

namespace Tests\Feature\Api\v3_0;

use App\Oil;
use App\User;
use App\Avatar;
use App\Favourite;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\v3_0\CurrentUser\CurrentUserController;

class CurrentUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_anonymous_is_unable_to_access_current_user()
    {
        $response = $this->json('GET', action([CurrentUserController::class, 'show'], 'en'));

        $response->assertStatus(401);
    }

    public function test_anonymous_is_unable_to_access_user_update()
    {
        $response = $this->json('PUT', action([CurrentUserController::class, 'update'], 'en'));

        $response->assertStatus(401);
    }

    public function test_can_access_current_user_info_when_logged_in()
    {
        $avatar = factory(Avatar::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $response = $this->json('GET', action([CurrentUserController::class, 'show'], 'en'));

        $response->assertStatus(200);
    }

    public function test_user_can_update_their_user_details()
    {
        $user = factory(User::class)->create();
        $avatar = factory(Avatar::class)->create();

        Passport::actingAs($user);

        $newEmail = 'new@email.com';
        $newName = 'New Name';
        $newAvatarId = $avatar->id;

        $response = $this->json('PUT', action([CurrentUserController::class, 'update'], 'en'), [
            'email' => $newEmail,
            'name' => $newName,
            'avatar_id' => $newAvatarId,
        ]);

        $response->assertStatus(200);

    }

    public function test_user_can_update_avatar_with_base64()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $response = $this->json('PUT', action([CurrentUserController::class, 'update'], 'en'), [
            'base64_image' => 'data:image/png;base64,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'
        ]);

        $response->assertStatus(200);
        $this->assertNotNull($user->avatar_url);
    }

    public function test_user_has_favourites_in_details()
    {
        $user = factory(User::class)->create();
        $avatar = factory(Avatar::class)->create();
        $oil = factory(Oil::class)->create();
        $user->favourites()->save(
            factory(Favourite::class)->create([
                'favouriteable_type' => Oil::class,
                'favouriteable_id' => $oil->id,
                'user_id' => $user->id
            ])
        );

        Passport::actingAs($user);

        $response = $this->json('GET', action([CurrentUserController::class, 'show'], 'en'));

        $response->assertStatus(200);

        $response->assertJsonFragment(
            ['favourites' => [$oil->uuid]]
        );
    }
}
