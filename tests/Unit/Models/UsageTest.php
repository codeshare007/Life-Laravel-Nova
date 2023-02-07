<?php

namespace Tests\Unit\Models;

use App\Usage;
use App\Ailment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsageTest extends TestCase
{
    use RefreshDatabase;

    public function test_usage_can_have_ailments()
    {
        $usage = factory(Usage::class)->create();
        $ailment = factory(Ailment::class)->create();

        $usage->ailments()->attach($ailment);

        $this->assertEquals(1, $usage->ailments->count());
    }
}
