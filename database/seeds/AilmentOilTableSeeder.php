<?php

use Illuminate\Database\Seeder;

class AilmentOilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Ailment::all()->each(function($ailment) {

            $oilIds = App\Oil::inRandomOrder()->limit(rand(1,3))->pluck('id');
            $ailment->oils()->attach($oilIds);

        });
    }
}
