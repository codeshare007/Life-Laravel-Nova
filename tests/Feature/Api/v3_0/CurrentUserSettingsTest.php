<?php

namespace Tests\Feature\Api\v3_0;

use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\v3_0\CurrentUser\CurrentUserSettingsController;

class CurrentUserSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_their_settings()
    {
        $settings = ['option' => 'value'];

        $user = factory(User::class)->create([
            'settings' => json_encode($settings),
        ]);

        Passport::actingAs($user);

        $response = $this->json('GET', action([CurrentUserSettingsController::class, 'show'], 'en'));
        $response->assertStatus(200);
        $response->assertJsonFragment($settings);
    }

    public function test_user_can_update_their_settings()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $newSettings = '{"option":"value"}';

        $response = $this->json('PUT', action([CurrentUserSettingsController::class, 'update'], 'en'), [
            'settings' => $newSettings,
        ]);

        $response->assertStatus(200);

        $user->fresh();

        $this->assertEquals($newSettings, $user->settings);
    }

    public function test_user_cannot_update_their_settings_with_invalid_json()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $newSettings = 'invalid json';

        $response = $this->json('PUT', action([CurrentUserSettingsController::class, 'update'], 'en'), [
            'settings' => $newSettings,
        ]);

        $response->assertStatus(422);
    }

    public function test_anonymous_is_unable_to_access_settings_update()
    {
        $response = $this->json('PUT', action([CurrentUserSettingsController::class, 'update'], 'en'));

        $response->assertStatus(401);
    }
}
