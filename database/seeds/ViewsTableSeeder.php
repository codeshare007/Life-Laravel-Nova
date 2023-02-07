<?php

use Illuminate\Database\Seeder;
use App\Enums\ViewableModels;

class ViewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $viewableModels = ViewableModels::getValues();

        foreach ($viewableModels as $modelClass) {

            $modelClass::all()->each(function($model) use ($modelClass) {

                factory(App\View::class, rand(1, 5))->create([
                    'viewable_id' => $model->id,
                    'viewable_type' => $modelClass,
                    'user_id' => function() {
                        return App\User::inRandomOrder()->first()->id;
                    },
                ]);
    
            });

        }
    }
}
