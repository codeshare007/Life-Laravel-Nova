<?php

use Illuminate\Database\Seeder;
use App\Enums\CategoryType;

class CategoryablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Recipe::all()->each(function($recipe) {

            $categoryId = App\Category::filterByType(CategoryType::Recipe)->inRandomOrder()->limit(1)->pluck('id');
            $recipe->categories()->attach($categoryId);

        });
    }
}
