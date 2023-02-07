<?php

namespace Tests\Feature\Api\v3_0;

use App\User;
use App\Remedy;
use Tests\TestCase;
use App\UserGeneratedContent;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Mail;
use App\Enums\UserGeneratedContentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\v3_0\UserGeneratedContent\UserGeneratedContentController;

class UserGeneratedContentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp($skipAuth = true)
    {
        parent::setUp($skipAuth);
        \Config::set('api.version', '3_0');

        Mail::fake();
    }

    public function test_user_can_add_content()
    {
        $this->signIn();
        $userGeneratedContent = factory(UserGeneratedContent::class)->make();

        $response = $this->json('POST', action([UserGeneratedContentController::class, 'store'], 'en'), $userGeneratedContent->toArray());
        $response->assertStatus(201);


        $this->assertEquals(1, UserGeneratedContent::count());
    }

    public function test_guest_cannot_add_content()
    {
        $userGeneratedContent = factory(UserGeneratedContent::class)->make();

        $response = $this->json('POST', action([UserGeneratedContentController::class, 'store'], 'en'), $userGeneratedContent->toArray());
        $response->assertStatus(401);

        $this->assertEquals(0, UserGeneratedContent::count());
    }

    public function test_user_can_delete_own_private_content()
    {
        $userGeneratedContent = factory(UserGeneratedContent::class)->create([
            'is_public' => false,
            'user_id' => $this->signIn()->id,
        ]);

        $url = action([UserGeneratedContentController::class, 'destroy'], ['en', $userGeneratedContent->uuid]);
        $response = $this->json('DELETE', $url);

        $response->assertStatus(204);
        $this->assertEquals(0, UserGeneratedContent::count());
    }

    public function test_user_can_delete_own_public_content_not_yet_approved()
    {
        $userGeneratedContent = factory(UserGeneratedContent::class)->create([
            'user_id' => $this->signIn()->id,
            'is_public' => true,
            'status' => UserGeneratedContentStatus::InReview,
        ]);

        $url = action([UserGeneratedContentController::class, 'destroy'], ['en', $userGeneratedContent->uuid]);
        $response = $this->json('DELETE', $url);

        $response->assertStatus(204);
        $this->assertEquals(0, UserGeneratedContent::count());
    }

    public function test_ugc_public_model_is_anonymised_when_deleted_if_public_and_approved()
    {
        $userGeneratedContent = factory(UserGeneratedContent::class)->create([
            'user_id' => $this->signIn()->id,
            'is_public' => true,
            'type' => 'Remedy',
        ]);

        $userGeneratedContent->approve();

        $url = action([UserGeneratedContentController::class, 'destroy'], ['en', $userGeneratedContent->uuid]);
        $response = $this->json('DELETE', $url);

        $response->assertStatus(204);
        $this->assertEquals(0, UserGeneratedContent::count());
        $this->assertEquals(1, Remedy::count());

        $remedy = $userGeneratedContent->publicModel->fresh();

        $this->assertEquals(config('app.anonymous_user_id.en'), $remedy->user_id);
    }

    public function test_user_cannot_delete_content_not_owned()
    {
        $user = $this->signIn();
        $anotherUser = factory(User::class)->create();
        Passport::actingAs($anotherUser);

        $userGeneratedContent = factory(UserGeneratedContent::class)->create([
            'user_id' => $user->id,
        ]);

        $url = action([UserGeneratedContentController::class, 'destroy'], ['en', $userGeneratedContent->uuid]);
        $response = $this->json('DELETE', $url);

        $response->assertForbidden();
        $this->assertEquals(1, UserGeneratedContent::count());
    }

    public function test_user_can_update_own_content()
    {
        $this->signIn();

        $userGeneratedContent = factory(UserGeneratedContent::class)->create([
            'user_id' => $this->signIn()->id,
        ]);

        $updatedData = [
            'name' => $userGeneratedContent->name.' (Updated)',
            'content' => [
                'ailment' => [1,2,3],
                'bodySystem' => [1],
                'ingredients' => [],
            ],
            'type' => 'Recipe',
            'is_public' => false,
        ];

        $url = action([UserGeneratedContentController::class, 'update'], ['en', $userGeneratedContent->uuid]);
        $response = $this->json('PUT', $url, $updatedData);
        $response->assertStatus(200);
        $userGeneratedContent = $userGeneratedContent->fresh();

        $this->assertEquals($updatedData['name'], $userGeneratedContent->name);
        $this->assertEquals($updatedData['content'], $userGeneratedContent->content);
    }

    public function test_ugc_is_set_to_in_review_when_updated()
    {
        $this->signIn();

        $userGeneratedContent = factory(UserGeneratedContent::class)->create([
            'user_id' => $this->signIn()->id,
            'status' => UserGeneratedContentStatus::Rejected,
        ]);

        $updatedData = [
            'name' => $userGeneratedContent->name.' (Updated)',
            'content' => [
                'ailment' => [1,2,3],
                'bodySystem' => [1],
                'ingredients' => [],
            ],
            'type' => 'Recipe',
            'is_public' => true,
        ];

        $url = action([UserGeneratedContentController::class, 'update'], ['en', $userGeneratedContent->uuid]);
        $response = $this->json('PUT',  $url, $updatedData);
        $response->assertStatus(200);
        $userGeneratedContent = $userGeneratedContent->fresh();

        $this->assertEquals(UserGeneratedContentStatus::InReview, $userGeneratedContent->status);
    }

    public function test_user_cannot_update_content_not_owned()
    {
        $user = $this->signIn();
        $anotherUser = factory(User::class)->create();
        Passport::actingAs($anotherUser);

        $userGeneratedContent = factory(UserGeneratedContent::class)->create([
            'user_id' => $user->id,
        ]);

        $updatedData = [
            'name' => $userGeneratedContent->name.' (Updated)',
            'content' => [
                'ailment' => [1,2,3],
                'bodySystem' => [1],
                'ingredients' => [],
            ],
            'type' => 'Recipe',
            'is_public' => false,
        ];

        $url = action([UserGeneratedContentController::class, 'update'], ['en', $userGeneratedContent->uuid]);
        $response = $this->json('PUT', $url, $updatedData);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_see_content()
    {
        $response = $this->json('GET', action([UserGeneratedContentController::class, 'index'], 'en'));
        $response->assertStatus(401);
    }

    public function test_user_can_see_own_content()
    {
        $this->signIn();
        $userGeneratedContent = factory(UserGeneratedContent::class)->create();

        $url = action([UserGeneratedContentController::class, 'show'], ['en', $userGeneratedContent->uuid]);
        
        $this->json('GET', $url)->assertStatus(200);
    }

    public function test_user_can_see_reason_for_rejection()
    {
        $this->signIn();

        $reasonSubject = 'Some Subject';
        $reasonDescription = 'You submitted this a few seconds ago!';

        $userGeneratedContent = factory(UserGeneratedContent::class)->create([
            'status' => UserGeneratedContentStatus::Rejected,
            'rejection_reason_subject' => $reasonSubject,
            'rejection_reason_description' => $reasonDescription,
        ]);

        $url = action([UserGeneratedContentController::class, 'show'], ['en', $userGeneratedContent->uuid]);
        $response = $this->json('GET', $url);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'rejection_reason_subject' => $reasonSubject,
                'rejection_reason_description' => $reasonDescription,
            ]
        ]);
    }

    public function test_user_can_add_content_with_image()
    {
        $this->signIn();

        $response = $this->json('POST', action([UserGeneratedContentController::class, 'store'], 'en'), [
            'base64_image' => 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==',
            'name' => 'Name',
            'type' => 'Recipe',
            'content' => [
                'key' => 'value',
            ],
            'status' => UserGeneratedContentStatus::InReview,
            'is_public' => 'true',
        ]);

        $response->assertStatus(201);
        $this->assertEquals(1, UserGeneratedContent::count());
        $this->assertNotNull(UserGeneratedContent::first()->image_url);
    }
}
