<?php

namespace App\Http\Controllers\Api\v2_1\User;

use App\User;
use Juampi92\APIResources\Facades\APIResource;
use App\Http\Controllers\Controller;

class UserCommunityController extends Controller
{
    public function index(User $user)
    {
        $user->load([
            'recipes',
            'remedies',
        ]);

        return api_resource('UserCommunityResource')->make($user);
    }
}
