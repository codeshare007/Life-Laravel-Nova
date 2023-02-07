<?php

namespace Tests\Unit\Models;

use App\Recipe;
use App\Category;
use App\Favourite;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_favourited_by_user()
    {
        $category = factory(Category::class)->create();
        $favourite = factory(Favourite::class)->create([
            'favouriteable_id' => $category->id,
            'favouriteable_type' => Category::class,
        ]);

        $this->assertCount(1, $category->favourites);
    }

    public function test_category_can_have_recipes()
    {
        $category = factory(Category::class)->create();
        $recipe = factory(Recipe::class)->create();

        $category->recipes()->attach($recipe);

        $this->assertCount(1, $category->recipes);
    }
}
