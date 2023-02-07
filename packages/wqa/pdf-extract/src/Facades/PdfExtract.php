<?php

namespace Wqa\PdfExtract\Facades;

use Illuminate\Support\Facades\Facade;

class PdfExtract extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pdf-extract';
    }
}
