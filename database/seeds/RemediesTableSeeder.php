<?php

use Illuminate\Database\Seeder;

class RemediesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Remedy::class, 5)->create();
    }
}
