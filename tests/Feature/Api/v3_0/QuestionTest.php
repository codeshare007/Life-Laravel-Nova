<?php

namespace Tests\Feature\Api\v3_0;

use App\Question;
use Tests\TestCase;
use App\Http\Requests\Question\StoreRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\v3_0\QuestionController;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_question()
    {
        $this->signIn();

        $this->json('POST', action([QuestionController::class, 'store'], 'en'), [
            'category' => $category = 'Oils',
            'title' => $title = 'Why does Lavender smell so good?',
            'description' => $description = 'Everyone likes Lavender, right!? I\'d like to know what the smell compounds are and why they are so appealing to humans.',
        ])->assertStatus(201);

        $this->assertEquals(1, Question::count());

        $question = Question::first();
        $this->assertEquals($category, $question->category);
        $this->assertEquals($title, $question->title);
        $this->assertEquals($description, $question->description);
        $this->assertEquals($this->user->id, $question->user->id);
    }

    public function test_store_questions_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(QuestionController::class, 'store', StoreRequest::class);
    }
}
