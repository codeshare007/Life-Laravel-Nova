<?php

namespace App\Http\Controllers\Api\v2_1\User;

use App\User;
use Juampi92\APIResources\Facades\APIResource;
use App\Http\Controllers\Api\Abstracts\QueryController;

class UserFavouriteController extends QueryController
{
    public static function getModelClass()
    {
        return User::class;
    }

    static function getResourceClass()
    {
        return APIResource::resolveClassname('FavouriteResource');
    }

    static function getSubModelRelationshipName()
    {
        return 'favourites';
    }

    static function getSortableColumns()
    {
        return ['latest'];
    }
}
