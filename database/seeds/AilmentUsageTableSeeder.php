<?php

use Illuminate\Database\Seeder;

class AilmentUsageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Usage::all()->each(function($usage) {
            $ailmentIds = App\Ailment::inRandomOrder()->limit(rand(1,3))->pluck('id');
            $usage->ailments()->attach($ailmentIds);

            $usage->save();
        });
    }
}
