<?php

namespace Tests\Unit\Models;

use App\Oil;
use App\Blend;
use App\Usage;
use App\Favourite;
use Tests\TestCase;
use App\Enums\ApiResource;
use App\SafetyInformation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlendTest extends TestCase
{
    use RefreshDatabase;

    private $basicResource;

    public function setUp()
    {
        parent::setUp();
        \Config::set('api.version', '1_2');

        $this->basicResource = api_resource(ApiResource::Blend);
    }

    public function test_blend_can_have_usages()
    {
        $blend = factory(Blend::class)->create();
        $usage = factory(Usage::class)->create([
            'useable_id' => $blend->id,
            'useable_type' => Blend::class,
        ]);

        $this->assertCount(1, $blend->usages);
    }

    public function test_blend_can_have_ingredients()
    {
        $blend = factory(Blend::class)->create();
        $oilA = factory(Oil::class)->create();
        $oilB = factory(Oil::class)->create();

        $blend->ingredients()->attach([$oilA->id, $oilB->id]);

        $this->assertCount(2, $blend->ingredients);
    }

    public function test_blend_can_be_favourited_by_user()
    {
        $blend = factory(Blend::class)->create();
        $favourite = factory(Favourite::class)->create([
            'favouriteable_id' => $blend->id,
            'favouriteable_type' => Blend::class,
        ]);

        $this->assertCount(1, $blend->favourites);
    }

    public function test_blend_can_have_safety_information()
    {
        $safetyInformation = factory(SafetyInformation::class)->create();
        $blend = factory(Blend::class)->create([
            'safety_information_id' => $safetyInformation->id,
        ]);

        $this->assertEquals($safetyInformation->id, $blend->safetyInformation->id);
    }

    public function test_when_blend_is_deleted_so_are_its_usages()
    {
        $blend = factory(Blend::class)->create();
        factory(Usage::class, 2)->create([
            'useable_id' => $blend->id,
            'useable_type' => Blend::class,
        ]);

        $this->assertCount(2, $blend->usages);

        $blend->delete();

        $this->assertCount(0, Usage::all());
    }
}
