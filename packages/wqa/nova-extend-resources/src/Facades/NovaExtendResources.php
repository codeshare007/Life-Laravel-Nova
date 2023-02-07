<?php

namespace wqa\NovaExtendResources\Facades;

use Illuminate\Support\Facades\Facade;

class NovaExtendResources extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'nova-extend-resources';
    }
}
