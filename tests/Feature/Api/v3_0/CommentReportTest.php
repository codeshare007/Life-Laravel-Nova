<?php

namespace Tests\Feature\Api\v3_0;

use App\User;
use Tests\TestCase;
use App\CommentReport;
use App\Enums\CommentReport\Status;
use App\Http\Requests\CommentReport\StoreRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\v3_0\Comment\CommentReportController;

class CommentReportTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        \Config::set('api.version', '3_0');
    }

    public function test_user_can_report_a_comment()
    {
        $commenter = factory(User::class)->create();

        $this->actingAs($this->signIn())
            ->post(action([CommentReportController::class, 'store'], 'en'), [
                'commenter_id' => $commenter->id,
                'reason' => 'Bad language',
                'comment' => 'Nobody likes that crappy oil!',
                'element' => 'some-element-uuid',
                'firebase_document' => '/path/to/document',
            ])
            ->assertStatus(201);

        $this->assertEquals(1, CommentReport::count());

        $commentReport = CommentReport::first();

        $this->assertEquals($commenter->id, $commentReport->commenter->id);
        $this->assertEquals('Bad language', $commentReport->reason);
        $this->assertEquals('Nobody likes that crappy oil!', $commentReport->comment);
        $this->assertEquals('some-element-uuid', $commentReport->element_uuid);
        $this->assertEquals('path/to/document', $commentReport->firebase_document);
        $this->assertEquals(Status::Open(), $commentReport->status);
    }

    public function test_store_comment_report_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(CommentReportController::class, 'store', StoreRequest::class);
    }

    public function test_guest_cannot_report_a_comment()
    {
        $response = $this->json('POST', action([CommentReportController::class, 'store'], 'en'), []);
        $response->assertStatus(401);
    }
}
