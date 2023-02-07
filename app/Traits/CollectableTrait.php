<?php

namespace App\Traits; 

use App\Collectable;

trait CollectableTrait
{
    public static function bootCollectableTrait() 
    {
        static::deleting(function ($model) {
            self::deleteCollectablesForModel($model);
        });
    }

    public static function isCollectable()
    {
        return true;
    }

    private static function deleteCollectablesForModel($model)
    {
        Collectable::where([
            'collectable_id' => $model->id,
            'collectable_type' => get_class($model),
        ])->delete();
    }
}