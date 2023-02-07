<?php

namespace Tests\Unit\Models;

use App\Oil;
use App\Solution;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SolutionTest extends TestCase
{
    use RefreshDatabase;

    public function test_solution_can_have_solutionable()
    {
        $solution = factory(Solution::class)->create([
            'solutionable_type' => Oil::class,
            'solutionable_id' => factory(Oil::class)->create()->id,
        ]);

        $this->assertInstanceOf(Oil::class, $solution->solutionable);
    }
}
