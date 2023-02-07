<?php

namespace Tests\Feature\Api\v3_0;

use App\Question;
use Tests\TestCase;
use App\Enums\Question\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\v3_0\Dashboard\DashboardQuestionController;

class DashboardQuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_featured_questions()
    {
        $this->signIn();

        $featuredQuestions = factory(Question::class, 2)->create([
            'is_featured' => true,
            'status' => Status::Approved(),
        ]);

        factory(Question::class, 2)->create([
            'is_featured' => false,
            'status' => Status::Approved(),
        ]);

        $this->get(action([DashboardQuestionController::class, 'index'], 'en'))
            ->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_get_featured_questions_in_correct_order()
    {
        $this->signIn();

        $featuredQuestionA = factory(Question::class)->create([
            'title' => 'A',
            'is_featured' => true,
            'status' => Status::Approved(),
        ]);

        $featuredQuestionB = factory(Question::class)->create([
            'title' => 'B',
            'is_featured' => true,
            'status' => Status::Approved(),
        ]);

        Question::setNewOrder([$featuredQuestionB->id, $featuredQuestionA->id]);

        $responseJson = $this->get(action([DashboardQuestionController::class, 'index'], 'en'))->json();

        $this->assertEquals($featuredQuestionB->title, $responseJson['data'][0]['title']);
        $this->assertEquals($featuredQuestionA->title, $responseJson['data'][1]['title']);
    }

    public function test_guest_cannot_get_featured_questions()
    {
        $this->get(action([DashboardQuestionController::class, 'index'], 'en'))->assertStatus(401);
    }
}
