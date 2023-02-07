<?php

use Illuminate\Support\Facades\Route;

// App v1.0 API
Route::group([
    'middleware' => ['auth:api', 'api.v:1_0'],
    'prefix'     => 'v1.0',
], function ($router) {
    require base_path('routes/api/v1.0.php');
});

// App v1.1 API
Route::group([
    'middleware' => ['api.v:1_1'],
    'prefix'     => 'v1.1',
], function ($router) {
    require base_path('routes/api/v1.1.php');
});

// App v1.2 API
Route::group([
    'middleware' => ['api.v:1_2'],
    'prefix'     => 'v1.2',
], function ($router) {
    require base_path('routes/api/v1.2.php');
});

/**
 * @OA\Info(title="Essential Life API", version="2.1")
 */
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
});

// Handle password reset token
Route::group([
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});

Route::group([
    'middleware' => ['user.lang'],
    'prefix'     => '{lang}',
], function ($router) {
    // Handle Authentication
    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');
    });

    // Handle password reset token
    Route::group([
        'middleware' => 'api',
        'prefix' => 'password'
    ], function () {
        Route::post('create', 'PasswordResetController@create');
        Route::get('find/{token}', 'PasswordResetController@find');
        Route::post('reset', 'PasswordResetController@reset');
    });

    // App v2.1 API
    Route::group([
        'middleware' => ['api.v:2_1'],
        'prefix'     => 'v2.1',
    ], function ($router) {
        require base_path('routes/api/v2.1.php');
    });

    // App v3.0 API
    Route::group([
        'middleware' => ['api.v:3_0'],
        'prefix'     => 'v3.0',
        'namespace' => '\\'
    ], function ($router) {
        require base_path('routes/api/v3.0.php');
    });
});

/* Deep Link Redirect */
Route::get('deeplink/{token}/{emailAddress}', function($token, $emailAddress) {
    return view('redirect-to-app', [
        'redirect_url' => 'essentiallife://forgottenPassword/'.$token.'/'.$emailAddress
    ]);
});


Route::get('deeplink-resource/{resource}/{uuid}', function($resource, $id) {
    // handle legacy links using id's and not uuid
    if (!str_contains($id, '-')) {
        $class = resourceToClass($resource);
        $model = (new $class)->find($id);
        if (! $model) {
            die();
        }
        $id = $model->uuid;
    }

    return view('redirect-to-app', [
        'redirect_url' => 'essentiallife://resource/'.$resource.'/'.$id
    ]);
});

Route::get('deeplink-to', function() {

    $path = trim(request()->path ?? null, '/');

    return view('redirect-to-app', [
        'redirect_url' => 'essentiallife://' . $path,
    ]);
});
