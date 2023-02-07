<?php

Route::group([
    'middleware' => ['api.token'],
], function () {
    Route::get('delta', 'DeltaController@index')->name('delta');
    Route::put('verify-receipt', 'VerifyReceiptController@verify');
    Route::apiResource('avatars', 'Avatar\AvatarController')->only('show', 'index');
    Route::apiResource('users', 'User\UserController')->only('show');
    Route::get('status', 'StatusController')->name('status');
});

Route::group([
    'middleware' => ['auth:api'],
], function () {
    Route::get('currentUser', 'CurrentUser\CurrentUserController@show')->name('currentUser.show');
    Route::put('currentUser', 'CurrentUser\CurrentUserController@update')->name('currentUser.update');
    Route::put('currentUser/password', 'CurrentUser\CurrentUserPasswordController@update')->name('currentUser.password.update');
    Route::get('currentUser/notifications', 'CurrentUser\CurrentUserNotificationController@index');
    Route::post('currentUser/used-app', 'CurrentUser\CurrentUserUsedAppController');
    Route::get('currentUser/settings', 'CurrentUser\CurrentUserSettingsController@show')->name('currentUser.settings.show');
    Route::put('currentUser/settings', 'CurrentUser\CurrentUserSettingsController@update')->name('currentUser.settings.update');
    Route::apiResource('favourites', 'Favourite\FavouriteController')->only('store', 'destroy');
    Route::apiResource('userGeneratedContent', 'UserGeneratedContent\UserGeneratedContentController');
    Route::apiResource('contentSuggestion', 'ContentSuggestion\ContentSuggestionController')->only('store');
    Route::apiResource('users.favourites', 'User\UserFavouriteController')->only('index');
    Route::get('/users/{user}/community', 'User\UserCommunityController@index')->name('users.community.index');
    Route::apiResource('notifications', 'Notification\NotificationController')->only('update');
    Route::apiResource('dashboard/cards', 'Dashboard\DashboardCardController')->only('index');
});
