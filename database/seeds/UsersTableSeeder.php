<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'name' => 'Ben Sampson',
            'email' => 'ben@sampo.co.uk',
            'is_admin' => true,
        ]);

        factory(App\User::class)->create([
            'name' => 'Matt Burns',
            'email' => 'matt.burns@wearewqa.com',
            'is_admin' => true,
        ]);

        factory(App\User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@wearewqa.com',
            'is_admin' => true,
        ]);
        
        factory(App\User::class, 5)->create();
    }
}
