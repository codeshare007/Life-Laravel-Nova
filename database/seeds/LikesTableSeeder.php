<?php

use Illuminate\Database\Seeder;
use App\Enums\LikeableModels;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $likeableModels = LikeableModels::getValues();

        App\User::all()->each(function($user) use ($likeableModels) {

            foreach ($likeableModels as $modelClass) {

                $modelClass::limit(100)->inRandomOrder()->get()->each(function($model) use ($modelClass, $user) {
    
                    factory(App\Like::class)->create([
                        'likeable_id' => $model->id,
                        'likeable_type' => $modelClass,
                        'user_id' => $user->id,
                    ]);
        
                });
    
            }

        });
    }
}
