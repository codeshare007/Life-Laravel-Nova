<?php

namespace Tests\Unit\FormRequests\Question;

use Tests\TestCase;
use App\Http\Requests\Question\StoreRequest;

class StoreRequestTest extends TestCase
{
    public function test_rules()
    {
        $this->assertEquals([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:250',
        ], (new StoreRequest)->rules());
    }
}