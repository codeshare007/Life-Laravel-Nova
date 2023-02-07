<?php

namespace App\Http\Controllers\Api\Avatar;

use App\Avatar;
use App\Http\Controllers\Controller;

class AvatarController extends Controller
{
    public function index()
    {
        $avatars = Avatar::all();

        return api_resource('AvatarResource')->collection($avatars);
    }
}
