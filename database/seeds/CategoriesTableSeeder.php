<?php

use Illuminate\Database\Seeder;
use App\Enums\CategoryType;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Category::class, 5)->create([
            'type' => CategoryType::Recipe,
        ]);
    }
}
