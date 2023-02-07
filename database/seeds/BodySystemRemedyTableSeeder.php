<?php

use Illuminate\Database\Seeder;

class BodySystemRemedyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\BodySystem::all()->each(function($bodySystem) {

            $remedyIds = App\Remedy::inRandomOrder()->limit(rand(1,3))->pluck('id');
            $bodySystem->remedies()->attach($remedyIds);

        });
    }
}
