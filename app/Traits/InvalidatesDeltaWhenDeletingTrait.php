<?php

namespace App\Traits; 

trait InvalidatesDeltaWhenDeletingTrait
{
    public static function bootInvalidatesDeltaWhenDeletingTrait() 
    {
        static::deleting(function ($model) {
            \Setting::set('delta_invalidated_at', now());
        });
    }
}