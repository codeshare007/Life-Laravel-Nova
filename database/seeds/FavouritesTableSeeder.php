<?php

use Illuminate\Database\Seeder;
use App\Enums\FavouriteableModels;

class FavouritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $favouriteableModels = FavouriteableModels::getValues();

        App\User::all()->each(function($user) use ($favouriteableModels) {

            foreach ($favouriteableModels as $modelClass) {

                $modelClass::limit(5)->inRandomOrder()->get()->each(function($model) use ($modelClass, $user) {
    
                    factory(App\Favourite::class)->create([
                        'favouriteable_id' => $model->id,
                        'favouriteable_type' => $modelClass,
                        'user_id' => $user->id,
                    ]);
        
                });
    
            }

        });
    }
}
