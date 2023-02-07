<?php

use Illuminate\Database\Seeder;

class OilsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Oil::class, 5)->create();
    }
}
