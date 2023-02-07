<?php

namespace Tests\Feature\Api\v3_0;

use App\BodySystem;
use Tests\TestCase;
use App\ContentSuggestion;
use App\Enums\ContentSuggestionMode;
use App\Enums\ContentSuggestionType;
use Illuminate\Support\Facades\Config;
use App\Enums\ContentSuggestionAssociationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\v3_0\ContentSuggestion\ContentSuggestionController;
use Illuminate\Foundation\Testing\TestResponse;

class ContentSuggestionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        Config::set('api.version', '3_0');
    }

    public function test_user_can_suggest_ailment()
    {
        $this->signIn();

        $response = $this->json('POST', action([ContentSuggestionController::class, 'store'], 'en'), [
            'name' => 'Dummy Suggestions',
            'type' => ContentSuggestionType::Ailment,
            'mode' => ContentSuggestionMode::getRandomValue(),
        ]);

        $response->assertStatus(201);

        $this->assertEquals(1, ContentSuggestion::count());
    }

    public function test_user_can_suggest_symptom()
    {
        $this->signIn();

        $response = $this->json('POST', action([ContentSuggestionController::class, 'store'], 'en'), [
            'name' => 'Dummy Suggestions',
            'type' => ContentSuggestionType::Symptom,
            'mode' => ContentSuggestionMode::getRandomValue(),
        ]);

        $response->assertStatus(201);

        $this->assertEquals(1, ContentSuggestion::count());
    }

    public function test_user_can_suggest_body_system()
    {
        $this->signIn();

        $response = $this->json('POST', action([ContentSuggestionController::class, 'store'], 'en'), [
            'name' => 'Dummy Suggestions',
            'type' => ContentSuggestionType::BodySystem,
            'mode' => ContentSuggestionMode::getRandomValue(),
        ]);

        $response->assertStatus(201);

        $this->assertEquals(1, ContentSuggestion::count());
    }

    public function test_user_cannot_suggest_content_with_invalid_type()
    {
        $this->signIn();

        $response = $this->json('POST', action([ContentSuggestionController::class, 'store'], 'en'), [
            'name' => 'Dummy Suggestions',
            'type' => 'Invalid Type',
            'mode' => ContentSuggestionMode::getRandomValue(),
        ]);

        $response->assertJsonValidationErrors('type');
    }

    public function test_user_cannot_suggest_content_with_invalid_mode()
    {
        $this->signIn();

        $response = $this->json('POST', action([ContentSuggestionController::class, 'store'], 'en'), [
            'name' => 'Dummy Suggestions',
            'type' => ContentSuggestionType::BodySystem,
            'mode' => 'Invalid mode',
        ]);

        $response->assertJsonValidationErrors('mode');
    }

    public function test_user_can_suggest_content_with_associated_body_system()
    {
        $this->signIn();

        $bodySystem = factory(BodySystem::class)->create();

        $response = $this->json('POST', action([ContentSuggestionController::class, 'store'], 'en'), [
            'name' => 'Dummy Suggestions',
            'type' => ContentSuggestionType::Ailment,
            'mode' => ContentSuggestionMode::getRandomValue(),
            'association_type' => ContentSuggestionAssociationType::BodySystem()->key,
            'association_id' => $bodySystem->uuid,
        ]);

        $response->assertStatus(201);

        $this->assertEquals(1, ContentSuggestion::count());

        $this->assertEquals($bodySystem->id, ContentSuggestion::first()->association->id);
    }

    public function test_guest_cannot_suggest_content()
    {
        $response = $this->json('POST', action([ContentSuggestionController::class, 'store'], 'en'), []);
        $response->assertStatus(401);
    }
}
