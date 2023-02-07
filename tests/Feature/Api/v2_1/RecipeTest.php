<?php

namespace Tests\Feature\Api\v2_1;

use App\View;
use App\Recipe;
use Tests\TestCase;
use App\Enums\ApiResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    private $basicResource;

    public function setUp()
    {
        parent::setUp();
        Config::set('api.version', '2_1');

        $this->basicResource = api_resource(ApiResource::Recipe);
        $this->signIn();
    }

    public function test_recipe_index()
    {
        $recipes = factory(Recipe::class, 2)->create()->fresh();
        $recipeResourceCollection = $this->basicResource->collection(Recipe::all())->jsonSerialize();

        $response = $this->json('GET', route('api.v2_1_recipes.index', 'en'));

        $response->assertStatus(200)->assertJsonFragment([
            'data' => $recipeResourceCollection,
        ]);

        $response->assertSee('alphabetical_data');
    }

    public function test_recipe_index_defaults_to_sort_by_name()
    {
        $recipe1 = factory(Recipe::class)->create(['name' => 'Cinnamon recipe']);
        $recipe2 = factory(Recipe::class)->create(['name' => 'Apple recipe']);
        $recipe3 = factory(Recipe::class)->create(['name' => 'Bergamot recipe']);

        $response = $this->json('GET', route('api.v2_1_recipes.index', 'en'));

        $response->assertStatus(200)->assertJsonCount(3, 'data');
        $responseJson = $response->json();

        $this->assertEquals($recipe2->name, $responseJson['data'][0]['name']);
        $this->assertEquals($recipe3->name, $responseJson['data'][1]['name']);
        $this->assertEquals($recipe1->name, $responseJson['data'][2]['name']);
    }

    public function test_recipe_index_an_exception_is_thrown_when_invalid_sort_by_value_is_passed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidSortQuery::class);

        $response = $this->json('GET', route('api.v2_1_recipes.index', ['lang' => 'en', 'sort' => 'invalid']));
    }
}
