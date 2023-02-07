<?php

namespace Tests\Feature\Api\v2_1;

use App\Oil;
use App\View;
use Tests\TestCase;
use App\Enums\ApiResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;

class OilTest extends TestCase
{
    use RefreshDatabase;

    private $basicResource;

    public function setUp()
    {
        parent::setUp();
        Config::set('api.version', '2_1');

        $this->basicResource = api_resource(ApiResource::Oil);
        $this->signIn();
    }

    public function test_oil_index()
    {
        factory(Oil::class, 2)->create();
        $oilResourceCollection = $this->basicResource->collection(Oil::all())->jsonSerialize();

        $response = $this->json('GET', route('api.v2_1_oils.index', 'en'));

        $response->assertStatus(200)->assertJsonFragment([
            'data' => $oilResourceCollection,
        ]);

        $response->assertSee('alphabetical_data');
    }

    public function test_oil_index_defaults_to_sort_by_name()
    {
        $oil1 = factory(Oil::class)->create(['name' => 'Cinnamon']);
        $oil2 = factory(Oil::class)->create(['name' => 'Apple']);
        $oil3 = factory(Oil::class)->create(['name' => 'Bergamot']);

        $response = $this->json('GET', route('api.v2_1_oils.index', 'en'));

        $response->assertStatus(200)->assertJsonCount(3, 'data');
        $responseJson = $response->json();

        $this->assertEquals($oil2->name, $responseJson['data'][0]['name']);
        $this->assertEquals($oil3->name, $responseJson['data'][1]['name']);
        $this->assertEquals($oil1->name, $responseJson['data'][2]['name']);
    }

    public function test_oil_index_an_exception_is_thrown_when_invalid_sort_by_value_is_passed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidSortQuery::class);

        $response = $this->json('GET', route('api.v2_1_oils.index', ['lang' => 'en', 'sort' => 'invalid']));
    }
}
