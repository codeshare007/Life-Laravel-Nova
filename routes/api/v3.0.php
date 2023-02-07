<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\v3_0\ElementController;
use App\Http\Controllers\Api\v3_0\QuestionController;
use App\Http\Controllers\Api\v3_0\User\UserController;
use App\Http\Controllers\Api\v3_0\Auth\LoginController;
use App\Http\Controllers\Api\v3_0\Auth\LogoutController;
use App\Http\Controllers\Api\v3_0\Auth\SignUpController;
use App\Http\Controllers\Api\v3_0\LatestUpdatesController;
use App\Http\Controllers\Api\v3_0\Search\SearchController;
use App\Http\Controllers\Api\v3_0\DownloadAppJsonController;
use App\Http\Controllers\Api\v3_0\User\UserSearchController;
use App\Http\Controllers\Api\v3_0\Comment\CommentReportController;
use App\Http\Controllers\Api\v3_0\DownloadRealmDatabaseController;
use App\Http\Controllers\Api\v3_0\CurrentUser\CurrentUserController;
use App\Http\Controllers\Api\v3_0\Dashboard\DashboardCardController;
use App\Http\Controllers\Api\v3_0\Favourite\ToggleFavouriteController;
use App\Http\Controllers\Api\v3_0\Notification\NotificationController;
use App\Http\Controllers\Api\v3_0\CurrentUser\CurrentUserFcmController;
use App\Http\Controllers\Api\v3_0\Subscription\VerifyReceiptController;
use App\Http\Controllers\Api\v3_0\Dashboard\DashboardQuestionController;
use App\Http\Controllers\Api\v3_0\CurrentUser\CurrentUserUsedAppController;
use App\Http\Controllers\Api\v3_0\CurrentUser\CurrentUserLanguageController;
use App\Http\Controllers\Api\v3_0\CurrentUser\CurrentUserPasswordController;
use App\Http\Controllers\Api\v3_0\CurrentUser\CurrentUserSettingsController;
use App\Http\Controllers\Api\v3_0\ContentSuggestion\ContentSuggestionController;
use App\Http\Controllers\Api\v3_0\CurrentUser\CurrentUserNotificationController;
use App\Http\Controllers\Api\v3_0\UserGeneratedContent\UserGeneratedContentController;

Route::group([
    'middleware' => ['api.token'],
], function () {
    Route::post('auth/login', LoginController::class);
    Route::post('auth/signup', SignUpController::class);
    Route::post('verify-receipt', VerifyReceiptController::class);
    Route::get('status', StatusController::class);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::get('download-database', DownloadRealmDatabaseController::class);
    Route::get('download-app-json', DownloadAppJsonController::class);
});

Route::group([
    'middleware' => ['auth:api'],
], function () {
    Route::post('auth/logout', LogoutController::class);
    Route::get('currentUser/notifications', [CurrentUserNotificationController::class, 'index']);
    Route::get('currentUser', [CurrentUserController::class, 'show']);
    Route::put('currentUser', [CurrentUserController::class, 'update']);
    Route::post('currentUser/language', CurrentUserLanguageController::class);
    Route::put('currentUser/password', [CurrentUserPasswordController::class, 'update']);
    Route::post('currentUser/used-app', CurrentUserUsedAppController::class);
    Route::get('currentUser/settings', [CurrentUserSettingsController::class, 'show']);
    Route::put('currentUser/settings', [CurrentUserSettingsController::class, 'update']);
    Route::post('currentUser/token', CurrentUserFcmController::class);
    Route::get('elements/{element}', [ElementController::class, 'show']);
    Route::get('latest-updates', LatestUpdatesController::class);
    Route::post('favourite', [ToggleFavouriteController::class, 'toggle']);
    Route::post('search', [SearchController::class, 'index']);
    Route::post('contentSuggestion', [ContentSuggestionController::class, 'store']);
    Route::post('question', [QuestionController::class, 'store']);
    Route::get('dashboard/featured-questions', [DashboardQuestionController::class, 'index']);
    Route::get('user/search', [UserSearchController::class, 'index']);
    Route::post('comment/report', [CommentReportController::class, 'store']);

    Route::apiResource('notifications', NotificationController::class)->only('update');
    Route::apiResource('userGeneratedContent', UserGeneratedContentController::class);
    Route::apiResource('dashboard/cards', DashboardCardController::class)->only('index');
});
