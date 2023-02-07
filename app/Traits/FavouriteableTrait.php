<?php

namespace App\Traits; 

use App\Favourite;

trait FavouriteableTrait
{
    public static function bootFavouriteableTrait() 
    {
        static::deleting(function ($model) {
            static::deleteFavouritesForModel($model);
        });
    }
    
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouriteable');
    }

    private static function deleteFavouritesForModel($model)
    {
        Favourite::where([
            'favouriteable_id' => $model->id,
            'favouriteable_type' => get_class($model),
        ])->delete();
    }
}