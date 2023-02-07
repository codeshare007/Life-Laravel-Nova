<?php

namespace Tests\Unit\FormRequests\User;

use App\Http\Requests\User\SearchRequest;
use Tests\TestCase;

class SearchRequestTest extends TestCase
{
    public function test_rules()
    {
        $this->assertEquals([
            'search' => 'required|string|min:3|max:200',
        ], (new SearchRequest())->rules());
    }
}