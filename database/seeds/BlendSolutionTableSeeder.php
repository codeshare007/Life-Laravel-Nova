<?php

use Illuminate\Database\Seeder;

class BlendSolutionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // App\Solution::filterByType(SolutionType::Blend)->each(function($solution) {

        //     $blendIds = App\Blend::inRandomOrder()->limit(rand(1,3))->pluck('id');
        //     $solution->blends()->attach($blendIds);

        // });
    }
}
