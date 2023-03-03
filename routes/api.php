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

            // Для участника курса (Участник и лидер)
            Route::group(['middleware' => 'forMembers'], function () {
                Route::get('/courses/{course}', 'CourseController@show');

                Route::group(['namespace' => 'Task'], function () {
                    Route::get('/courses/{course}/tasks', 'TaskController@index');
                    Route::get('/courses/{course}/tasks/{task}', 'TaskController@show');

                    Route::group(['namespace' => 'File'], function () {
                        Route::get('/courses/{course}/tasks/{task}/files', 'FileController@index');
                    });

                    Route::group(['namespace' => 'Decision'], function () {

                        // Для создателя ответа и лидера
                        Route::group(['middleware' => 'creatorDecisionAndLeader'], function () {
                            Route::get('courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@show');
                        });

                        // Только для создателя ответа
                        Route::group(['middleware' => 'creatorDecision'], function () {
                            Route::patch('courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@update');
                            Route::delete('courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@destroy');
                        });
                    });
                });
            });

            // Только для участника (не лидера) курса
            Route::group(['middleware' => 'onlyMember'], function () {
                Route::group(['namespace' => 'Task'], function () {
                    Route::group(['namespace' => 'Decision'], function () {
                        Route::post('courses/{course}/tasks/{task}/decisions', 'DecisionController@store');
                    });
                });
            });

            // Только для лидера курса
            Route::group(['middleware' => 'onlyLeader'], function () {
                Route::patch('/courses/{course}', 'CourseController@update');
                Route::post('/courses/{course}/background', 'CourseController@storeBackground');
                Route::delete('/courses/{course}/background', 'CourseController@DeleteBackground');
                Route::delete('/courses/{course}', 'CourseController@destroy');

                Route::group(['namespace' => 'Task'], function () {
                    Route::post('/courses/{course}/tasks', 'TaskController@store');
                    Route::patch('/courses/{course}/tasks/{task}', 'TaskController@update');
                    Route::delete('/courses/{course}/tasks/{task}', 'TaskController@destroy');

                    Route::group(['namespace' => 'File'], function () {
                        Route::get('/courses/{course}/tasks/{task}/files', 'FileController@index');
                        Route::post('/courses/{course}/tasks/{task}/files', 'FileController@store');
                        Route::delete('/courses/{course}/tasks/{task}/files/{file}', 'FileController@destroy');
                    });

                    Route::group(['namespace' => 'Decision'], function () {
                        Route::get('courses/{course}/tasks/{task}/decisions', 'DecisionController@index');
                    });
                });
            });
        });
    });
});
