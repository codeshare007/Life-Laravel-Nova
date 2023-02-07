<?php

use Illuminate\Database\Seeder;

class BlendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Blend::class, 5)->create();
    }
}
