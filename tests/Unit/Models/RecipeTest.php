<?php

namespace Tests\Unit\Models;

use App\User;
use App\Recipe;
use App\Category;
use App\Favourite;
use Tests\TestCase;
use App\Enums\CategoryType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_recipe_can_be_favourited_by_user()
    {
        $recipe = factory(Recipe::class)->create();
        $favourite = factory(Favourite::class)->create([
            'favouriteable_id' => $recipe->id,
            'favouriteable_type' => Recipe::class,
        ]);

        $this->assertCount(1, $recipe->favourites);
    }

    public function test_recipe_can_have_categories()
    {
        $recipe = factory(Recipe::class)->create();
        $category = factory(Category::class)->create([
            'type' => CategoryType::Recipe,
        ]);
        $recipe->categories()->attach($category);

        $this->assertCount(1, $recipe->categories);
    }

    public function test_recipe_can_belong_to_a_user()
    {
        $user = factory(User::class)->create();
        $recipe = factory(Recipe::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $recipe->user->id);
    }

    public function test_can_check_recipe_is_user_generated()
    {
        $userGeneratedRecipe = factory(Recipe::class)->create([
            'user_id' => factory(User::class)->create()->id,
        ]);
        $recipe = factory(Recipe::class)->create();

        $this->assertTrue($userGeneratedRecipe->is_user_generated);
        $this->assertFalse($recipe->is_user_generated);
    }
}
