<?php

use Illuminate\Database\Seeder;

class OilSourcingMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Oil::all()->each(function($oil) {

            $sourcingMethodIds = App\SourcingMethod::inRandomOrder()->limit(2)->pluck('id');
            $oil->sourcingMethods()->attach($sourcingMethodIds);

        });
    }
}
