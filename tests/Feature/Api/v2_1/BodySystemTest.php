<?php

namespace Tests\Feature\Api\v2_1;

use App\View;
use App\BodySystem;
use Tests\TestCase;
use App\Enums\ApiResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BodySystemTest extends TestCase
{
    use RefreshDatabase;

    private $basicResource;

    public function setUp()
    {
        parent::setUp();
        Config::set('api.version', '2_1');

        $this->basicResource = api_resource(ApiResource::BodySystem);
        $this->signIn();
    }

    public function test_oil_index_defaults_to_sort_by_name()
    {
        $bodySystem1 = factory(BodySystem::class)->create(['name' => 'Cinnamon']);
        $bodySystem2 = factory(BodySystem::class)->create(['name' => 'Apple']);
        $bodySystem3 = factory(BodySystem::class)->create(['name' => 'Bergamot']);

        $response = $this->json('GET', route('api.v2_1_body-systems.index', 'en'));

        $response->assertStatus(200)->assertJsonCount(3, 'data');
        $responseJson = $response->json();

        $this->assertEquals($bodySystem2->name, $responseJson['data'][0]['name']);
        $this->assertEquals($bodySystem3->name, $responseJson['data'][1]['name']);
        $this->assertEquals($bodySystem1->name, $responseJson['data'][2]['name']);
    }
}
