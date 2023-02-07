<?php

namespace Tests\Feature\Api\v2_1;

use App\Enums\ApiResource;
use App\User;
use App\View;
use App\Favourite;
use App\Recipe;
use App\Category;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Enums\CategoryType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private $basicResource;

    public function setUp()
    {
        parent::setUp();
        Config::set('api.version', '2_1');

        $this->basicResource = api_resource(ApiResource::Category);
        $this->signIn();
    }

    public function test_category_index()
    {
        $categories = factory(Category::class, 2)->create();
        $categoryResourceCollection = $this->basicResource->collection(Category::all())->jsonSerialize();

        $response = $this->json('GET', route('api.v2_1_categories.index', 'en'));

        $response->assertStatus(200)->assertJsonFragment([
            'data' => $categoryResourceCollection,
        ]);

        $response->assertSee('alphabetical_data');
    }

    public function test_category_index_defaults_to_sort_by_name()
    {
        $category1 = factory(Category::class)->create(['name' => 'Cinnamon']);
        $category2 = factory(Category::class)->create(['name' => 'Apple']);
        $category3 = factory(Category::class)->create(['name' => 'Bergamot']);

        $response = $this->json('GET', route('api.v2_1_categories.index', 'en'));

        $response->assertStatus(200)->assertJsonCount(3, 'data');
        $responseJson = $response->json();

        $this->assertEquals($category2->name, $responseJson['data'][0]['name']);
        $this->assertEquals($category3->name, $responseJson['data'][1]['name']);
        $this->assertEquals($category1->name, $responseJson['data'][2]['name']);
    }
}
