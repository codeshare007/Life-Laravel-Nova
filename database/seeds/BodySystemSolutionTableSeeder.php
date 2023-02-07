<?php

use Illuminate\Database\Seeder;

class BodySystemSolutionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\BodySystem::all()->each(function($bodySystem) {

            $solutionIds = App\Solution::inRandomOrder()->limit(rand(1,3))->pluck('id');
            $bodySystem->solutions()->attach($solutionIds);

        });
    }
}
