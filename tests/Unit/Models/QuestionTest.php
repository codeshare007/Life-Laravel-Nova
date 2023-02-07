<?php

namespace Tests\Unit\Models;

use App\User;
use App\Question;
use Tests\TestCase;
use Tests\Traits\TestsFields;
use App\Enums\Question\Status;
use App\Events\QuestionApprovedEvent;
use App\Events\QuestionRejectedEvent;
use Illuminate\Support\Facades\Event;
use App\Services\QuestionService\QuestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;
    use TestsFields;

    public function test_question_has_uuid()
    {
        $this->assertHasField(Question::class, 'uuid');
    }

    public function test_question_has_title()
    {
        $this->assertHasField(Question::class, 'title');
    }

    public function test_question_has_category()
    {
        $this->assertHasField(Question::class, 'category');
    }

    public function test_question_has_description()
    {
        $this->assertHasField(Question::class, 'description');
    }

    public function test_question_has_is_featured()
    {
        $this->assertHasField(Question::class, 'is_featured');
    }

    public function test_question_has_firebase_document()
    {
        $this->assertHasField(Question::class, 'firebase_document');
    }

    public function test_question_has_status()
    {
        $this->assertHasField(Question::class, 'status');
    }

    public function test_question_has_rejection_reason_subject()
    {
        $this->assertHasField(Question::class, 'rejection_reason_subject');
    }

    public function test_question_has_rejection_reason_description()
    {
        $this->assertHasField(Question::class, 'rejection_reason_description');
    }

    public function test_question_can_belong_to_a_user()
    {
        $user = factory(User::class)->create();
        $question = factory(Question::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $question->user->id);
    }

    public function test_can_scope_by_featured()
    {
        factory(Question::class, 2)->create(['is_featured' => true]);
        factory(Question::class, 2)->create(['is_featured' => false]);

        $this->assertCount(4, Question::all());
        $this->assertCount(2, Question::featured()->get());
    }

    public function test_can_scope_by_approved()
    {
        Event::fake();
        
        factory(Question::class, 2)->create(['status' => Status::InReview]);
        factory(Question::class, 2)->create(['status' => Status::Approved]);

        $this->assertCount(4, Question::all());
        $this->assertCount(2, Question::approved()->get());
    }

    public function test_can_approve_question()
    {
        Event::fake();

        $question = factory(Question::class)->create(['status' => Status::InReview]);

        $mock = $this->mock(QuestionService::class);
        $mock->shouldReceive('insert')
            ->once()
            ->with($question)
            ->andReturn();

        $question->approve();

        $this->assertEquals(Status::Approved(), $question->status);
    }

    public function test_approve_question_fires_question_approved_event()
    {
        Event::fake();
        
        $question = factory(Question::class)->create(['status' => Status::InReview]);

        $question->approve();

        $this->assertEquals(Status::Approved(), $question->status);

        Event::assertDispatched(QuestionApprovedEvent::class);
    }

    public function test_can_reject_question()
    {
        Event::fake();

        $question = factory(Question::class)->create(['status' => Status::InReview]);

        $question->reject('Subject', 'Longer description');

        $this->assertEquals(Status::Rejected(), $question->status);
        $this->assertEquals('Subject', $question->rejection_reason_subject);
        $this->assertEquals('Longer description', $question->rejection_reason_description);
    }

    public function test_reject_question_fires_question_rejected_event()
    {
        Event::fake();
        
        $question = factory(Question::class)->create(['status' => Status::InReview]);

        $question->reject('Subject', 'description...');

        $this->assertEquals(Status::Rejected(), $question->status);

        Event::assertDispatched(QuestionRejectedEvent::class);
    }

    public function test_question_with_firebase_document_is_deleted_from_firebase_when_deleted()
    {
        $question = factory(Question::class)->create([
            'status' => Status::Approved(),
            'firebase_document' => 'path/to/document',
        ]);

        $mock = $this->mock(QuestionService::class);
        $mock->shouldReceive('delete')
            ->once()
            ->with($question)
            ->andReturn();

        $question->delete();
    }

    public function test_question_without_firebase_document_is_not_deleted_from_firebase_when_deleted()
    {
        $question = factory(Question::class)->create([
            'status' => Status::InReview(),
            'firebase_document' => null,
        ]);

        $mock = $this->mock(QuestionService::class);
        $mock->shouldNotReceive('delete');

        $question->delete();
    }

    public function test_question_with_firebase_document_updates_firebase_when_it_is_updated()
    {
        $question = factory(Question::class)->create([
            'status' => Status::Approved(),
            'firebase_document' => 'path/to/document',
        ]);

        $mock = $this->mock(QuestionService::class);
        $mock->shouldReceive('update')
            ->once()
            ->with($question)
            ->andReturn();

        $question->update([
            'title' => 'Updated title',
        ]);
    }

    public function test_can_unapprove_question()
    {
        $question = factory(Question::class)->create(['status' => Status::Approved]);

        $mock = $this->mock(QuestionService::class);
        $mock->shouldReceive('delete')
            ->once()
            ->with($question)
            ->andReturn();

        $question->unapprove();

        $this->assertEquals(Status::InReview(), $question->status);
    }
}
