<?php

namespace App\Http\Controllers\Api\CurrentUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidApiParameterException;
use App\Http\Requests\CurrentUser\Settings\UpdateRequest;

class CurrentUserSettingsController extends Controller
{
    public function show()
    {
        return api_resource('UserSettingsResource')->make(Auth::user());
    }

    public function update(UpdateRequest $request)
    {
        Auth::user()->update($request->validated());
    }
}
