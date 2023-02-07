<?php

namespace Tests\Feature\Api\v2_1;

use App\View;
use App\Supplement;
use Tests\TestCase;
use App\Enums\ApiVersion;
use App\Enums\ApiResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;

class SupplementTest extends TestCase
{
    use RefreshDatabase;

    private $basicResource;

    public function setUp()
    {
        parent::setUp();
        Config::set('api.version', '2_1');

        $this->basicResource = api_resource(ApiResource::Supplement);
        $this->signIn();
    }

    public function test_supplement_index()
    {
        factory(Supplement::class, 2)->create();
        $supplementResourceCollection = $this->basicResource->collection(
            Supplement::with(Supplement::getDefaultIncludes(ApiVersion::v2_1()))
                ->get()
        )->jsonSerialize();

        $response = $this->json('GET', route('api.v2_1_supplements.index', 'en'));

        $response->assertStatus(200)->assertJsonFragment([
            'data' => $supplementResourceCollection,
        ]);

        $response->assertSee('alphabetical_data');
    }

    public function test_supplement_index_defaults_to_sort_by_name()
    {
        $supplement1 = factory(Supplement::class)->create(['name' => 'Cinnamon']);
        $supplement2 = factory(Supplement::class)->create(['name' => 'Apple']);
        $supplement3 = factory(Supplement::class)->create(['name' => 'Bergamot']);

        $response = $this->json('GET', route('api.v2_1_supplements.index', 'en'));

        $response->assertStatus(200)->assertJsonCount(3, 'data');
        $responseJson = $response->json();

        $this->assertEquals($supplement2->name, $responseJson['data'][0]['name']);
        $this->assertEquals($supplement3->name, $responseJson['data'][1]['name']);
        $this->assertEquals($supplement1->name, $responseJson['data'][2]['name']);
    }

    public function test_supplement_index_an_exception_is_thrown_when_invalid_sort_by_value_is_passed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidSortQuery::class);

        $response = $this->json('GET', route('api.v2_1_supplements.index', ['lang' => 'en', 'sort' => 'invalid']));
    }
}
