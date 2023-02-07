<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('elements', 'Wqa\SeedDownload\Http\Controllers\DownloadController@elements');
Route::get('community-authors', 'Wqa\SeedDownload\Http\Controllers\DownloadController@communityAuthors');
Route::get('languages', 'Wqa\SeedDownload\Http\Controllers\LanguagesController');
