<?php

use Illuminate\Database\Seeder;

class AilmentBlendTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Ailment::all()->each(function($ailment) {

            $blendIds = App\Blend::inRandomOrder()->limit(rand(1,3))->pluck('id');
            $ailment->blends()->attach($blendIds);

        });
    }
}
