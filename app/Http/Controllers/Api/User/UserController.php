<?php

namespace App\Http\Controllers\Api\User;

use App\Ailment;
use App\Enums\UserGeneratedContentStatus;
use App\Favourite;
use App\Recipe;
use App\Remedy;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function show($id)
    {
        $user = $this->user
            ->with('content.recipe', 'content.remedy', 'remedies.bodySystems')
            ->find($id);
        return api_resource('UserResource')->make($user);
    }
}
