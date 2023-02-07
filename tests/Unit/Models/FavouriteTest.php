<?php

namespace Tests\Unit\Models;

use App\Oil;
use App\Favourite;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavouriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_a_favouriteable_model_is_deleted_all_of_the_favourites_associated_with_it_are_also_deleted()
    {
        $model = factory(Oil::class)->create();
        $favourite = factory(Favourite::class, 2)->create([
            'favouriteable_id' => $model->id,
            'favouriteable_type' => get_class($model),
        ]);

        $this->assertCount(2, $model->favourites);

        $model->delete();

        $this->assertCount(0, Favourite::all());
    }
}
