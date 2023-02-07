<?php

namespace Wqa\NovaExtendFields\Facades;

use Illuminate\Support\Facades\Facade;

class NovaExtendFields extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'nova-extend-fields';
    }
}
