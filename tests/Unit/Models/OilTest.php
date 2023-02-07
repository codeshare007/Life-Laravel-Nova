<?php

namespace Tests\Unit\Models;

use App\Oil;
use App\Tag;
use App\Blend;
use App\Usage;
use App\Favourite;
use Tests\TestCase;
use App\Enums\TagType;
use App\SourcingMethod;
use App\SafetyInformation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OilTest extends TestCase
{
    use RefreshDatabase;

    public function test_oil_can_have_usages()
    {
        $oil = factory(Oil::class)->create();
        $usage = factory(Usage::class)->create([
            'useable_id' => $oil->id,
            'useable_type' => Oil::class,
        ]);

        $this->assertCount(1, $oil->usages);
    }

    public function test_oil_can_have_blends_with_oils()
    {
        $oilA = factory(Oil::class)->create();
        $oilB = factory(Oil::class)->create();
        $oilC = factory(Oil::class)->create();

        $oilA->blendsWith()->attach([$oilB->id, $oilC->id]);

        $this->assertCount(2, $oilA->blendsWith);
    }

    public function test_oil_can_be_favourited_by_user()
    {
        $oil = factory(Oil::class)->create();
        $favourite = factory(Favourite::class)->create([
            'favouriteable_id' => $oil->id,
            'favouriteable_type' => Oil::class,
        ]);

        $this->assertCount(1, $oil->favourites);
    }

    public function test_oil_can_have_found_in_blends()
    {
        $oil = factory(Oil::class)->create();
        $blendA = factory(Blend::class)->create();
        $blendB = factory(Blend::class)->create();

        $oil->foundInBlends()->attach([$blendA->id, $blendB->id]);

        $this->assertCount(2, $oil->foundInBlends);
    }

    public function test_oil_can_have_properties()
    {
        $oil = factory(Oil::class)->create();
        $property = factory(Tag::class)->create([
            'type' => TagType::Property,
        ]);
        $oil->properties()->attach($property);

        $this->assertCount(1, $oil->properties);
    }

    public function test_oil_can_have_sourcing_methods()
    {
        $oil = factory(Oil::class)->create();
        $sourcingMethod = factory(SourcingMethod::class)->create();
        $oil->sourcingMethods()->attach($sourcingMethod);

        $this->assertCount(1, $oil->sourcingMethods);
    }

    public function test_oil_can_have_safety_information()
    {
        $safetyInformation = factory(SafetyInformation::class)->create();
        $oil = factory(Oil::class)->create([
            'safety_information_id' => $safetyInformation->id,
        ]);

        $this->assertEquals($safetyInformation->id, $oil->safetyInformation->id);
    }

    public function test_when_oil_is_deleted_so_are_its_properties_relations()
    {
        $oil = factory(Oil::class)->create();
        $properties = factory(Tag::class, 2)->create([
            'type' => TagType::Property,
        ]);
        $oil->properties()->attach($properties);

        $this->assertCount(2, $oil->properties);

        $oil->delete();

        $this->assertCount(0, \DB::table('tagables')->get());
    }

    public function test_when_oil_is_deleted_so_are_its_usages()
    {
        $oil = factory(Oil::class)->create();
        $usages = factory(Usage::class, 2)->create([
            'useable_id' => $oil->id,
            'useable_type' => Oil::class,
        ]);

        $this->assertCount(2, $oil->usages);

        $oil->delete();

        $this->assertCount(0, Usage::all());
    }
}
