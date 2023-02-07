<?php

use Illuminate\Database\Seeder;

class SourcingMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\SourcingMethod::class, 5)->create();
    }
}
