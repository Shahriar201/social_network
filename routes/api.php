<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Auth routes
Route::group([], function() {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['middleware' => 'auth:api'], function() {
    // Create page routes
    Route::post('page/create', 'PageController@createPage');

    // Follow route
    Route::post('follow/person/{personId}', 'FollowController@followPerson');
    Route::post('follow/page/{pageId}', 'FollowController@followPage');

    // Post routes
    Route::post('person/attach-post', 'PostController@personPostPublish');
    Route::post('page/{pageId}/attach-post', 'PostController@personPostPublishInAPage');

    // Person routes
    Route::get('person/feed', 'PersonController@personFeed');
});
