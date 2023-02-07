<?php

namespace Tests\Feature\Api\v2_1;

use App\View;
use App\Blend;
use Tests\TestCase;
use App\Enums\ApiResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;

class BlendTest extends TestCase
{
    use RefreshDatabase;

    private $basicResource;

    public function setUp()
    {
        parent::setUp();
        Config::set('api.version', '2_1');

        $this->basicResource = api_resource(ApiResource::Blend);
        $this->signIn();
    }

    public function test_blend_index()
    {
        factory(Blend::class, 2)->create()->fresh();
        $blendResourceCollection = $this->basicResource->collection(Blend::all())->jsonSerialize();

        $response = $this->json('GET', route('api.v2_1_blends.index', 'en'));

        $response->assertStatus(200)->assertJsonFragment([
            'data' => $blendResourceCollection,
        ]);
    }

    public function test_blend_index_defaults_to_sort_by_name()
    {
        $blend1 = factory(Blend::class)->create(['name' => 'Cinnamon']);
        $blend2 = factory(Blend::class)->create(['name' => 'Apple']);
        $blend3 = factory(Blend::class)->create(['name' => 'Bergamot']);

        $response = $this->json('GET', route('api.v2_1_blends.index', 'en'));

        $response->assertStatus(200)->assertJsonCount(3, 'data');
        $responseJson = $response->json();

        $this->assertEquals($blend2->name, $responseJson['data'][0]['name']);
        $this->assertEquals($blend3->name, $responseJson['data'][1]['name']);
        $this->assertEquals($blend1->name, $responseJson['data'][2]['name']);
    }

    public function test_blend_index_an_exception_is_thrown_when_invalid_sort_by_value_is_passed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidSortQuery::class);

        $response = $this->json('GET', route('api.v2_1_blends.index', ['lang' => 'en', 'sort' => 'invalid']));
    }
}
