<?php

use Illuminate\Database\Seeder;

class AilmentRelatedAilmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Ailment::all()->each(function($ailment) {

            $relatedAilmentIds = App\Ailment::inRandomOrder()->limit(rand(1,3))->pluck('id');
            $ailment->relatedAilments()->attach($relatedAilmentIds);

        });
    }
}
