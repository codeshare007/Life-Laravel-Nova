<?php

use Illuminate\Database\Seeder;

class OilSolutionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // App\Solution::filterByType(SolutionType::Oil)->each(function($solution) {

        //     $oilIds = App\Oil::inRandomOrder()->limit(rand(1,3))->pluck('id');
        //     $solution->oils()->attach($oilIds);

        // });
    }
}
