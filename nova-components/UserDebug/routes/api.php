<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Wqa\UserDebug\Http\Resources\UserResource;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/

Route::get('/{id}', function (Request $request) {
    return UserResource::make(User::findOrFail($request->id));
});
