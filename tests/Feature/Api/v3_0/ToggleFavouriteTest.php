<?php

namespace Tests\Feature\Api\v3_0;

use App\Oil;
use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\v3_0\Favourite\ToggleFavouriteController;

class ToggleFavouriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_favourite()
    {
        $user = factory(User::class)->create();
        $oil = factory(Oil::class)->create();

        Passport::actingAs($user);

        $oil = factory(Oil::class)->create();
        $response = $this->json('POST', action([ToggleFavouriteController::class, 'toggle'], 'en'), [
            'uuid' => $oil->uuid,
            'favourite' => true,
        ]);

        $response->assertStatus(200);

        $this->assertTrue(
            $user->favourites
                ->where('favouriteable_type', Oil::class)
                ->where('favouriteable_id', $oil->id)
                ->isNotEmpty()
        );

        $response = $this->json('POST', action([ToggleFavouriteController::class, 'toggle'], 'en'), [
            'uuid' => $oil->uuid,
            'favourite' => false,
        ]);

        $response->assertStatus(200);

        $user = $user->fresh();

        $this->assertTrue(
            $user->favourites
                ->where('favouriteable_type', Oil::class)
                ->where('favouriteable_id', $oil->id)
                ->isEmpty()
        );
    }

    public function test_anonymous_cannot_toggle_a_favourite()
    {
        $this->json('POST', action([ToggleFavouriteController::class, 'toggle'], 'en'))
            ->assertStatus(401);
    }
}
