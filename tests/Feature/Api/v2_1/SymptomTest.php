<?php

namespace Tests\Feature\Api\v2_1;

use App\View;
use App\Ailment;
use Tests\TestCase;
use App\Enums\ApiVersion;
use App\Enums\AilmentType;
use App\Enums\ApiResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;

class SymptomTest extends TestCase
{
    use RefreshDatabase;

    private $basicResource;

    public function setUp()
    {
        parent::setUp();
        Config::set('api.version', '2_1');

        $this->basicResource = api_resource(ApiResource::Symptom);
        $this->signIn();
    }

    public function test_symptom_index()
    {
        factory(Ailment::class, 2)->create([
            'ailment_type' => AilmentType::Symptom
        ]);
        $symptomResourceCollection = $this->basicResource->collection(
            Ailment::with(Ailment::getDefaultIncludes(ApiVersion::v2_1()))
                ->where('ailment_type', AilmentType::Symptom)
                ->get()
        )->jsonSerialize();

        $response = $this->json('GET', route('api.v2_1_symptoms.index', 'en'));

        $response->assertStatus(200)->assertJsonFragment([
            'data' => $symptomResourceCollection,
        ]);

        $response->assertSee('alphabetical_data');
    }

    public function test_symptom_index_defaults_to_sort_by_name()
    {
        $symptom1 = factory(Ailment::class)->create([
            'name' => 'Cinnamon',
            'ailment_type' => AilmentType::Symptom
        ]);
        $symptom2 = factory(Ailment::class)->create([
            'name' => 'Apple',
            'ailment_type' => AilmentType::Symptom
        ]);
        $symptom3 = factory(Ailment::class)->create([
            'name' => 'Bergamot',
            'ailment_type' => AilmentType::Symptom
        ]);

        $response = $this->json('GET', route('api.v2_1_symptoms.index', 'en'));

        $response->assertStatus(200)->assertJsonCount(3, 'data');
        $responseJson = $response->json();

        $this->assertEquals($symptom2->name, $responseJson['data'][0]['name']);
        $this->assertEquals($symptom3->name, $responseJson['data'][1]['name']);
        $this->assertEquals($symptom1->name, $responseJson['data'][2]['name']);
    }

    public function test_symptom_index_an_exception_is_thrown_when_invalid_sort_by_value_is_passed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidSortQuery::class);

        $response = $this->json('GET', route('api.v2_1_symptoms.index', ['lang' => 'en', 'sort' => 'invalid']));
    }
}
