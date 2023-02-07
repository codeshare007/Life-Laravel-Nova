<?php

use Illuminate\Database\Seeder;

class AilmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Ailment::class, 5)->create();
    }
}
