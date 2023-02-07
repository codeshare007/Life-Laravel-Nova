<?php

use Illuminate\Database\Seeder;
use App\Enums\CollectableModels;

class CollectablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Collection::all()->each(function($collection) {
            
            $collectableModels = CollectableModels::getValues();

            foreach($collectableModels as $model) {
                factory(App\Collectable::class)->create([
                    'collection_id' => $collection->id,
                    'collectable_type' => $model,
                    'collectable_id' => function() use ($model) {
                        return $model::inRandomOrder()->first();
                    }
                ]);
            }

        });
    }
}
