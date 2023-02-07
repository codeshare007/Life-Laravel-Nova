<?php

use Illuminate\Database\Seeder;

class CollectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::all()->each(function($user) {
            factory(App\Collection::class, 1)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
