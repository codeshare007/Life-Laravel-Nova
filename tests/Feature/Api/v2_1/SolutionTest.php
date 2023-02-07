<?php

namespace Tests\Feature\Api\v2_1;

use App\Oil;
use App\Blend;
use App\Recipe;
use App\Solution;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SolutionTest extends TestCase
{
    use RefreshDatabase;

    private $basicResource;

    public function setUp()
    {
        parent::setUp();
        Config::set('api.version', '2_1');
        $this->signIn();
    }

    public function test_solutions_index()
    {
        $solution = factory(Solution::class)->create([
            'solutionable_type' => Oil::class,
            'solutionable_id' => factory(Oil::class)->create()->id,
        ]);
        $solution2 = factory(Solution::class)->create([
            'solutionable_type' => Blend::class,
            'solutionable_id' => factory(Blend::class)->create()->id,
        ]);
        // @todo - Find why supplements causes 500
//        $solution3 = factory(Solution::class)->create([
//            'solutionable_type' => Supplement::class,
//            'solutionable_id' => factory(Supplement::class)->create()->id,
//        ]);

        $response = $this->json('GET', route('api.v2_1_solutions.index', 'en'));

        $response->assertStatus(200)->assertJsonFragment([
            $solution->solutionable->uuid,
        ]);

        $response->assertJsonFragment([$solution2->solutionable->uuid]);

        $response->assertSee('alphabetical_data');
    }

    public function test_solutions_index_defaults_to_sort_by_name()
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
}
