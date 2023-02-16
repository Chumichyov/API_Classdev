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
        // Курс
        Route::group(['namespace' => 'Course'], function () {
            Route::get('/courses', 'CourseController@index');
            Route::post('/courses', 'CourseController@store');

            // Для участника курса
            Route::group(['middleware' => 'forMember'], function () {
                Route::get('/courses/{course}', 'CourseController@show');
            });

            // Для лидера курса
            Route::group(['middleware' => 'forLeader'], function () {
                Route::patch('/courses/{course}', 'CourseController@update');
                Route::delete('/courses/{course}', 'CourseController@destroy');
            });
        });
    });
});
