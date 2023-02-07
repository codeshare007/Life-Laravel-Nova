<?php

use Illuminate\Database\Seeder;
use App\Enums\TagType;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Tag::class, 5)->create([
            'type' => TagType::Property,
        ]);
    }
}
