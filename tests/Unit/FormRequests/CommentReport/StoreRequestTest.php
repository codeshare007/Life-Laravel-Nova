<?php

namespace Tests\Unit\FormRequests\CommentReport;

use Tests\TestCase;
use App\Http\Requests\CommentReport\StoreRequest;

class StoreRequestTest extends TestCase
{
    public function test_rules()
    {
        $this->assertEquals([
            'reporter_id' => 'required|int|exists:users,id',
            'commenter_id' => 'required|int|exists:users,id',
            'reason' => 'required|string|max:400',
            'comment' => 'required|string',
            'element_uuid' => 'sometimes|string',
            'firebase_document' => 'required|string',
        ], (new StoreRequest)->rules());
    }
}