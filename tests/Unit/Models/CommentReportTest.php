<?php

namespace Tests\Unit\Models;

use App\User;
use Tests\TestCase;
use App\CommentReport;
use Tests\Traits\TestsFields;
use App\Enums\CommentReport\Status;
use App\Enums\CommentReport\ActionTaken;
use App\Services\CommentService\CommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentReportTest extends TestCase
{
    use RefreshDatabase;
    use TestsFields;
    
    public function test_comment_report_can_belong_to_a_reporter()
    {
        $user = factory(User::class)->create();
        $report = factory(CommentReport::class)->create([
            'reporter_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $report->reporter->id);
    }

    public function test_comment_report_can_belong_to_a_commenter()
    {
        $user = factory(User::class)->create();
        $report = factory(CommentReport::class)->create([
            'commenter_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $report->commenter->id);
    }

    public function test_comment_report_can_have_reason()
    {
        $this->assertHasField(CommentReport::class, 'reason');
    }

    public function test_comment_report_can_have_comment()
    {
        $this->assertHasField(CommentReport::class, 'comment');
    }

    public function test_comment_report_can_have_status()
    {
        $this->assertHasField(CommentReport::class, 'status');
    }

    public function test_comment_report_can_have_action_taken()
    {
        $this->assertHasField(CommentReport::class, 'action_taken');
    }

    public function test_comment_report_can_have_element_uuid()
    {
        $this->assertHasField(CommentReport::class, 'element_uuid');
    }

    public function test_comment_report_can_have_firebase_document()
    {
        $this->assertHasField(CommentReport::class, 'firebase_document');
    }

    public function test_can_moderate_delete()
    {
        /** @var CommentReport */
        $report = factory(CommentReport::class)->create();
        $this->assertEquals(Status::Open(), $report->status);

        $mock = $this->mock(CommentService::class);
        $mock->shouldReceive('delete')
            ->once()
            ->with($report->firebase_document)
            ->andReturn();

        $report->moderateDelete();

        $this->assertEquals(Status::Resolved(), $report->status);
        $this->assertEquals(ActionTaken::Deleted(), $report->action_taken);
    }

    public function test_can_moderate_replace()
    {
        /** @var CommentReport */
        $report = factory(CommentReport::class)->create();

        $this->assertEquals(Status::Open(), $report->status);

        $mock = $this->mock(CommentService::class);
        $mock->shouldReceive('updateBody')
            ->once()
            ->with($report->firebase_document, 'Replacement text')
            ->andReturn();

        $report->moderateReplace('Replacement text');

        $this->assertEquals(Status::Resolved(), $report->status);
        $this->assertEquals(ActionTaken::Replaced(), $report->action_taken);
    }

    public function test_can_resolve_with_no_action()
    {
        /** @var CommentReport */
        $report = factory(CommentReport::class)->create();

        $this->assertEquals(Status::Open(), $report->status);

        $report->resolveWithNoAction();

        $this->assertEquals(Status::Resolved(), $report->status);
        $this->assertEquals(ActionTaken::None(), $report->action_taken);
    }
}
