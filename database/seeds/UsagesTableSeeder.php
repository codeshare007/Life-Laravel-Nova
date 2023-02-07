<?php

use Illuminate\Database\Seeder;

class UsagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Oil::all()->each(function($oil) {
            factory(App\Usage::class, rand(1, 3))->create([
                'useable_id' => $oil->id,
                'useable_type' => \App\Oil::class,
            ]);
        });

        App\Blend::all()->each(function($blend) {
            factory(App\Usage::class, rand(1, 3))->create([
                'useable_id' => $blend->id,
                'useable_type' => \App\Blend::class,
            ]);
        });
    }
}
