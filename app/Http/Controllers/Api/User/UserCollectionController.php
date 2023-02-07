<?php

namespace App\Http\Controllers\Api\User;

use App\User;
use App\Http\Controllers\Api\Abstracts\QueryController;

class UserCollectionController extends QueryController
{
    public static function getModelClass()
    {
        return User::class;
    }

    static function getSubModelRelationshipName()
    {
        return 'collections';
    }
}
