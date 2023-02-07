<?php

namespace Tests\Feature\Api\v2_1;

use App\View;
use App\Remedy;
use Tests\TestCase;
use App\Enums\ApiVersion;
use App\Enums\ApiResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;

class RemedyTest extends TestCase
{
    use RefreshDatabase;

    private $basicResource;

    public function setUp()
    {
        parent::setUp();
        Config::set('api.version', '2_1');

        $this->basicResource = api_resource(ApiResource::Remedy);
        $this->signIn();
    }

    public function test_remedy_index()
    {
        $remedies = factory(Remedy::class, 2)->create();

        $remedyResourceCollection = $this->basicResource->collection(
            Remedy::with(Remedy::getDefaultIncludes(ApiVersion::v2_1()))->get()
        )->jsonSerialize();

        $response = $this->json('GET', route('api.v2_1_remedies.index', 'en'));

        $response->assertStatus(200)->assertJsonFragment([
            'data' => $remedyResourceCollection,
        ]);

        $response->assertSee('alphabetical_data');
    }

    public function test_remedy_index_defaults_to_sort_by_name()
    {
        $remedy1 = factory(Remedy::class)->create(['name' => 'Cccc']);
        $remedy2 = factory(Remedy::class)->create(['name' => 'Aaaa']);
        $remedy3 = factory(Remedy::class)->create(['name' => 'Bbbb']);

        $response = $this->json('GET', route('api.v2_1_remedies.index', 'en'));

        $response->assertStatus(200)->assertJsonCount(3, 'data');
        $responseJson = $response->json();

        $this->assertEquals($remedy2->name, $responseJson['data'][0]['name']);
        $this->assertEquals($remedy3->name, $responseJson['data'][1]['name']);
        $this->assertEquals($remedy1->name, $responseJson['data'][2]['name']);
    }

    public function test_remedy_index_an_exception_is_thrown_when_invalid_sort_by_value_is_passed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidSortQuery::class);

        $response = $this->json('GET', route('api.v2_1_remedies.index', ['lang' => 'en', 'sort' => 'invalid']));
    }
}
