<?php

Route::group([
    'middleware' => ['api.token'],
], function () {
    Route::get('delta', 'DeltaController@index')->name('delta');
    Route::put('verify-receipt', 'VerifyReceiptController@verify');
    Route::apiResource('avatars', 'v2_1\Avatar\AvatarController')->only('index');
    Route::get('status', 'StatusController')->name('status');
    Route::get('meta', 'v2_1\ElementController@meta');
    Route::apiResource('users', 'v2_1\User\UserController')->only('show');
});

Route::group([
    'middleware' => ['auth:api'],
], function () {
    Route::apiResource('notifications', 'v2_1\Notification\NotificationController')->only('update');
    Route::get('currentUser/notifications', 'v2_1\CurrentUser\CurrentUserNotificationController@index');
    Route::get('currentUser', 'v2_1\CurrentUser\CurrentUserController@show')->name('currentUser.show');
    Route::put('currentUser', 'v2_1\CurrentUser\CurrentUserController@update')->name('currentUser.update');
    Route::put('currentUser/password', 'v2_1\CurrentUser\CurrentUserPasswordController@update')->name('currentUser.password.update');
    Route::post('currentUser/used-app', 'v2_1\CurrentUser\CurrentUserUsedAppController');
    Route::get('currentUser/settings', 'v2_1\CurrentUser\CurrentUserSettingsController@show')->name('currentUser.settings.show');
    Route::put('currentUser/settings', 'v2_1\CurrentUser\CurrentUserSettingsController@update')->name('currentUser.settings.update');
    Route::post('currentUser/token', 'v2_1\CurrentUser\CurrentUserController@fcmToken')->name('currentUser.token.update');
    Route::apiResource('userGeneratedContent', 'v2_1\UserGeneratedContent\UserGeneratedContentController')->names([
        'index' => 'v2_1_ugc.index',
        'store' => 'v2_1_ugc.store',
        'show' => 'v2_1_ugc.show',
        'update' => 'v2_1_ugc.update',
        'destroy' => 'v2_1_ugc.destroy',
    ]);
    Route::apiResource('contentSuggestion', 'v2_1\ContentSuggestion\ContentSuggestionController')
        ->only('store')
        ->names(['store' => 'v2_1_contentSuggestion']);
    Route::apiResource('users.favourites', 'v2_1\User\UserFavouriteController')->only('index');
    Route::apiResource('dashboard/cards', 'v2_1\Dashboard\DashboardCardController')->only('index');

    Route::apiResource('elements', 'v2_1\ElementController')->only('show');
    Route::get('latest-updates', 'v2_1\ElementController@getLatestUpdates');

    Route::apiResource('oils', 'v2_1\Oil\OilController')->names([
        'index' => 'v2_1_oils.index',
    ]);
    Route::apiResource('blends', 'v2_1\Blend\BlendController')->names([
        'index' => 'v2_1_blends.index',
    ]);
    Route::apiResource('remedies', 'v2_1\Remedy\RemedyController')->names([
        'index' => 'v2_1_remedies.index',
    ]);
    Route::apiResource('ailments', 'v2_1\Ailment\AilmentController')->names([
        'index' => 'v2_1_ailments.index',
    ]);
    Route::apiResource('body-systems', 'v2_1\BodySystem\BodySystemController')->names([
        'index' => 'v2_1_body-systems.index',
    ]);
    Route::apiResource('categories', 'v2_1\Category\CategoryController')->names([
        'index' => 'v2_1_categories.index',
    ]);
    Route::apiResource('properties', 'v2_1\Property\PropertyController')->names([
        'index' => 'v2_1_properties.index',
    ]);
    Route::apiResource('recipes', 'v2_1\Recipe\RecipeController')->names([
        'index' => 'v2_1_recipes.index',
    ]);
    Route::apiResource('supplements', 'v2_1\Supplement\SupplementController')->names([
        'index' => 'v2_1_supplements.index',
    ]);
    Route::apiResource('symptoms', 'v2_1\Symptom\SymptomController')->names([
        'index' => 'v2_1_symptoms.index',
    ]);
    Route::apiResource('solutions', 'v2_1\Solution\SolutionController')->names([
        'index' => 'v2_1_solutions.index',
    ]);

    Route::post('favourite', 'v2_1\Favourite\FavouriteController@toggleFavourite')
        ->name('toggle-favourite');

    Route::post('/search', 'v2_1\Search\SearchController@search')
        ->name('v2_1_search');

    Route::apiResource('regions', 'v2_1\Region\RegionController')->names([
        'index' => 'v2_1_regions.index',
    ]);
});
