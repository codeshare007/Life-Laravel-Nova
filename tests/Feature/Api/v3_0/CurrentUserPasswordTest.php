<?php

namespace Tests\Feature\Api\v3_0;

use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\OriginalPasswordMismatchException;
use App\Http\Controllers\Api\v3_0\CurrentUser\CurrentUserPasswordController;

class CurrentUserPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_their_password()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('abc123abc'),
        ]);

        Passport::actingAs($user);

        $newPassword = 'newPassword';

        $response = $this->json('PUT', action([CurrentUserPasswordController::class, 'update'], 'en'), [
            'original_password' => 'abc123abc',
            'new_password' => $newPassword,
        ]);

        $response->assertStatus(200);

        $user->fresh();

        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    public function test_user_cannot_update_their_password_if_original_password_is_incorrect()
    {
        $this->withoutExceptionHandling();
        $this->expectException(OriginalPasswordMismatchException::class);

        $newPassword = 'newPassword';
        $originalPassword = 'originalPassword';

        $user = factory(User::class)->create([
            'password' => Hash::make($originalPassword),
        ]);

        Passport::actingAs($user);

        $response = $this->json('PUT', action([CurrentUserPasswordController::class, 'update'], 'en'), [
            'original_password' => 'incorrect',
            'new_password' => $newPassword,
        ]);

        $user->fresh();

        $this->assertTrue(Hash::check($originalPassword, $user->password), 'The original password has been changed');
    }
}
