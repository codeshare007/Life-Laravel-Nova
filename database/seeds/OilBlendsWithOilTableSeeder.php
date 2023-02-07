<?php

use Illuminate\Database\Seeder;

class OilBlendsWithOilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Oil::all()->each(function($oil) {

            $oilBlendsWithIds = App\Oil::inRandomOrder()->limit(3)->pluck('id');
            $oil->blendsWith()->attach($oilBlendsWithIds);

        });
    }
}
