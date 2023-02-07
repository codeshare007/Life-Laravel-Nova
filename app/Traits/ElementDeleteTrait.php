<?php

namespace App\Traits;

use App\Element;

trait ElementDeleteTrait
{
    public static function bootElementDeleteTrait()
    {
        static::deleted(function ($model) {
            Element::find($model->uuid)->delete();
        });
    }
}
