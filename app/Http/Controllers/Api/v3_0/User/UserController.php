<?php

namespace App\Http\Controllers\Api\v3_0\User;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserController extends Controller
{
    /**
     * @param $id
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function show($lang, User $user)
    {
        $user->load([
            'content' => function(HasMany $query) {
                $query->whereHasPublicModel()->with([
                    'recipe',
                    'remedy',
                ]);
            },
        ]);

        return api_resource('UserResource')->make($user);
    }
}
