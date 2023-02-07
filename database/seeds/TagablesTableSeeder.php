<?php

use App\Enums\TagType;
use Illuminate\Database\Seeder;

class TagablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Oil::all()->each(function($oil) {

            $tagIds = App\Tag::filterByType(TagType::Property)->inRandomOrder()->limit(rand(1,5))->pluck('id');
            $oil->properties()->attach($tagIds);

        });
    }
}
