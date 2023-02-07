<?php

namespace Tests\Unit\Models;

use App\Oil;
use App\Blend;
use App\Remedy;
use App\Ailment;
use App\Favourite;
use App\BodySystem;
use Tests\TestCase;
use Tests\Traits\TestsFields;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AilmentTest extends TestCase
{
    use RefreshDatabase;
    use TestsFields;

    public function test_ailment_can_be_favourited_by_user()
    {
        $ailment = factory(Ailment::class)->create();
        factory(Favourite::class)->create([
            'favouriteable_id' => $ailment->id,
            'favouriteable_type' => Ailment::class,
        ]);

        $this->assertCount(1, $ailment->favourites);
    }

    public function test_ailment_can_have_body_systems()
    {
        $ailment = factory(Ailment::class)->create();
        $bodySystem = factory(BodySystem::class)->create();
        $ailment->bodySystems()->attach($bodySystem);

        $this->assertCount(1, $ailment->bodySystems);
    }

    public function test_ailment_can_have_remedies()
    {
        $ailment = factory(Ailment::class)->create();
        $remedy = factory(Remedy::class)->create();
        $ailment->remedies()->save($remedy);

        $this->assertCount(1, $ailment->remedies);
    }

    public function test_ailment_can_have_oils()
    {
        $ailment = factory(Ailment::class)->create();
        $oil = factory(Oil::class)->create();
        $ailment->oils()->attach($oil);

        $this->assertCount(1, $ailment->oils);
    }

    public function test_ailment_can_have_blends()
    {
        $ailment = factory(Ailment::class)->create();
        $blend = factory(Blend::class)->create();
        $ailment->blends()->attach($blend);

        $this->assertCount(1, $ailment->blends);
    }

    public function test_ailment_can_have_related_ailments()
    {
        $ailment = factory(Ailment::class)->create();
        $relatedAilment = factory(Ailment::class)->create();
        $ailment->relatedAilments()->attach($relatedAilment);

        $this->assertCount(1, $ailment->relatedAilments);
    }
}
