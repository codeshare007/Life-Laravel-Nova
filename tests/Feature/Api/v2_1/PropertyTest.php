<?php

namespace Tests\Feature\Api\v2_1;

use App\Tag;
use Tests\TestCase;
use App\Enums\TagType;
use App\Enums\ApiVersion;
use App\Enums\ApiResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    private $basicResource;

    public function setUp()
    {
        parent::setUp();
        Config::set('api.version', '2_1');

        $this->basicResource = api_resource(ApiResource::Tag);
        $this->signIn();
    }

    public function test_properties_index()
    {
        factory(Tag::class, 2)->create([
            'type' => TagType::Property
        ]);
        $propertyResourceCollection = $this->basicResource->collection(
            Tag::with(Tag::getDefaultIncludes(ApiVersion::v2_1()))
                ->where('type', TagType::Property)
                ->get()
        )->jsonSerialize();

        $response = $this->json('GET', route('api.v2_1_properties.index', 'en'));

        $response->assertStatus(200)->assertJsonFragment([
            'data' => $propertyResourceCollection,
        ]);

        $response->assertSee('alphabetical_data');
    }
}
