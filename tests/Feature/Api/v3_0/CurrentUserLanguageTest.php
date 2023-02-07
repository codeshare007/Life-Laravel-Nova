<?php

namespace Tests\Feature\Api\v3_0;

use App\Oil;
use App\User;
use App\Favourite;
use Tests\TestCase;
use App\Enums\UserLanguage;
use Laravel\Passport\Passport;
use App\Events\UserSwitchedLanguage;
use Illuminate\Support\Facades\Event;
use Tests\Traits\RefreshAllDatabases;
use App\Services\LanguageDatabaseService;
use App\Http\Controllers\Api\v3_0\CurrentUser\CurrentUserLanguageController;

class CurrentUserLanguageTest extends TestCase
{
    use RefreshAllDatabases;

    public function test_user_can_swap_language()
    {
        $dbService = new LanguageDatabaseService();
        $fromLanguage = UserLanguage::English();
        $toLanguage = UserLanguage::Spanish();

        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $this->json('POST', action(CurrentUserLanguageController::class, [$fromLanguage]), [
            'language' => UserLanguage::Spanish,
        ])->assertSessionHasNoErrors();

        $dbService->setLanguage($fromLanguage);

        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
        ]);

        $dbService->setLanguage($toLanguage);

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    public function test_user_cannot_switch_to_invalid_language()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $this->json('POST', action(CurrentUserLanguageController::class, [UserLanguage::English()]), [
            'language' => 'invalid_language',
        ])->assertStatus(422);
    }

    public function test_user_cannot_switch_to_same_language()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $this->json('POST', action(CurrentUserLanguageController::class, [UserLanguage::English()]), [
            'language' => UserLanguage::English()->value,
        ])->assertStatus(200)->assertJson([
            'User already set to this language',
        ]);
    }

    public function test_user_favourites_are_transferred_to_new_language_database()
    {
        $dbService = new LanguageDatabaseService();
        $fromLanguage = UserLanguage::English();
        $toLanguage = UserLanguage::Spanish();

        $user = factory(User::class)->create();
        $oil = factory(Oil::class)->create();
        $user->favourites()->save(
            factory(Favourite::class)->create([
                'favouriteable_type' => Oil::class,
                'favouriteable_id' => $oil->id,
                'user_id' => $user->id
            ])
        );

        $this->assertCount(1, $user->favourites);

        Passport::actingAs($user);

        $this->json('POST', action(CurrentUserLanguageController::class, [$fromLanguage]), [
            'language' => UserLanguage::Spanish,
        ])->assertSessionHasNoErrors();

        $dbService->setLanguage($toLanguage);

        $user = User::where('email', $user->email)->first();
        
        $this->assertCount(1, $user->favourites);
    }

    public function test_switching_language_triggers_user_switched_language_event()
    {
        Event::fake();

        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $this->json('POST', action(CurrentUserLanguageController::class, [UserLanguage::English()]), [
            'language' => UserLanguage::Spanish,
        ])->assertSessionHasNoErrors();

        Event::assertDispatched(UserSwitchedLanguage::class);
    }
}
