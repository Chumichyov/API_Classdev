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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('/register', 'RegisterController');
        Route::post('/login', 'LoginController');
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['namespace' => 'Course'], function () {
            Route::get('/courses', 'CourseController@index');
            Route::post('/courses', 'CourseController@store');
        });
    });
});
