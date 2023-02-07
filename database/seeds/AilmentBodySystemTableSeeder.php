<?php

use Illuminate\Database\Seeder;

class AilmentBodySystemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\BodySystem::all()->each(function($bodySystem) {

            $ailmentIds = App\Ailment::inRandomOrder()->limit(rand(1,3))->pluck('id');
            $bodySystem->ailments()->attach($ailmentIds);

        });
    }
}
