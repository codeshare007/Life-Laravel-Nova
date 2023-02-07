<?php

use Illuminate\Database\Seeder;

class AilmentRemedyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Ailment::all()->each(function($ailment) {

            $remedyIds = App\Remedy::inRandomOrder()->limit(rand(1,3))->pluck('id');
            $ailment->remedies()->attach($remedyIds);

        });
    }
}
