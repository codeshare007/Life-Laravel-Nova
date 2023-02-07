<?php

namespace Tests\Unit\Models;

use App\Remedy;
use App\Ailment;
use App\Solution;
use App\BodySystem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BodySystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_body_system_can_have_solutions()
    {
        $bodySystem = factory(BodySystem::class)->create();
        $solution = factory(Solution::class)->create();
        $bodySystem->solutions()->attach($solution);

        $this->assertCount(1, $bodySystem->solutions);
    }

    public function test_body_system_can_have_remedies()
    {
        $bodySystem = factory(BodySystem::class)->create();
        $remedy = factory(Remedy::class)->create();
        $bodySystem->remedies()->attach($remedy);

        $this->assertCount(1, $bodySystem->remedies);
    }

    public function test_body_system_can_have_ailments()
    {
        $bodySystem = factory(BodySystem::class)->create();
        $ailment = factory(Ailment::class)->create();
        $bodySystem->ailments()->attach($ailment);

        $this->assertCount(1, $bodySystem->ailments);
    }
}
