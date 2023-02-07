<?php

use Illuminate\Database\Seeder;

class BlendOilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Blend::all()->each(function($blend) {

            $ingredientOils = App\Oil::inRandomOrder()->limit(3)->pluck('id');
            $blend->ingredients()->attach($ingredientOils);

        });
    }
}
