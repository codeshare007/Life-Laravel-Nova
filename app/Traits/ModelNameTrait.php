<?php

namespace App\Traits;

trait ModelNameTrait
{
    public function fullModelName()
    {
        return (new \ReflectionClass(get_called_class()));
    }

    public function getApiModelName()
    {
        return (new \ReflectionClass(get_called_class()))->getShortName();
    }
}
