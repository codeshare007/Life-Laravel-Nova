<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Api\Abstracts\QueryController;

class CategoryController extends QueryController
{
    public static function getModelClass()
    {
        return Category::class;
    }
}
