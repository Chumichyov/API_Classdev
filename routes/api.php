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
    // Для неавторизованных
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('/login', 'LoginController');
        Route::post('/register', 'RegisterController');
    });

    // -----------------------------------------------------------------------------------------------

    // Для авторизованных
    Route::group(['middleware' => 'auth:api'], function () {
        // Для всех
        Route::group(['namespace' => 'Course'], function () {
            Route::get('/courses', 'CourseController@index');
            Route::post('/courses', 'CourseController@store');
            Route::post('/connection', 'CourseController@connection');
            Route::post('/connection/{link}', 'CourseController@connection');
        });

        Route::group(['namespace' => 'Invitation'], function () {
            Route::post('/invitations/{invitation}', 'InvitationController@accept');
            Route::get('/invitations', 'InvitationController@index');
        });

        // --------------------------------------------------------------------------------------------

        // Для участников
        Route::group(['middleware' => 'forMembers'], function () {
            Route::group(['namespace' => 'Course'], function () {
                Route::get('/courses/{course}', 'CourseController@show');
                Route::post('/courses/{course}/leave', 'CourseController@leave');
            });

            Route::group(['namespace' => 'Decision'], function () {
            });

            Route::group(['namespace' => 'File'], function () {
                Route::get('/courses/{course}/tasks/{task}/files', 'FileController@index');
            });

            Route::group(['namespace' => 'Setting'], function () {
            });

            Route::group(['namespace' => 'Task'], function () {
                Route::get('/courses/{course}/tasks', 'TaskController@index');
                Route::get('/courses/{course}/tasks/{task}', 'TaskController@show');
            });
        });

        // --------------------------------------------------------------------------------------------

        // Для создателя ответа
        Route::group(['middleware' => 'creatorDecision'], function () {
            Route::group(['namespace' => 'Course'], function () {
            });

            Route::group(['namespace' => 'Decision'], function () {
                Route::patch('/courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@update');
                Route::delete('/courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@destroy');
            });

            Route::group(['namespace' => 'File'], function () {
                Route::post('/courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionFileController@store');
                Route::delete('/courses/{course}/tasks/{task}/decisions/{decision}/files/{file}', 'DecisionFileController@destroy');
            });

            Route::group(['namespace' => 'Setting'], function () {
            });

            Route::group(['namespace' => 'Task'], function () {
            });
        });

        // --------------------------------------------------------------------------------------------

        // Для создателя ответа и лидера
        Route::group(['middleware' => 'creatorDecisionAndLeader'], function () {
            Route::group(['namespace' => 'Course'], function () {
            });

            Route::group(['namespace' => 'Decision'], function () {
                Route::get('/courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@show');
            });

            Route::group(['namespace' => 'File'], function () {
            });

            Route::group(['namespace' => 'Folder'], function () {
                Route::get('/courses/{course}/tasks/{task}/decisions/{decision}/folders/{folder}', 'DecisionFolderController@show');
            });

            Route::group(['namespace' => 'Setting'], function () {
            });

            Route::group(['namespace' => 'Task'], function () {
            });
        });

        // --------------------------------------------------------------------------------------------

        // Не лидер
        Route::group(['middleware' => 'onlyMember'], function () {
            Route::group(['namespace' => 'Course'], function () {
            });

            Route::group(['namespace' => 'Decision'], function () {
                Route::post('/courses/{course}/tasks/{task}/decisions', 'DecisionController@store');
            });

            Route::group(['namespace' => 'File'], function () {
            });

            Route::group(['namespace' => 'Setting'], function () {
            });

            Route::group(['namespace' => 'Task'], function () {
            });
        });

        // --------------------------------------------------------------------------------------------

        // Лидер
        Route::group(['middleware' => 'onlyLeader'], function () {
            Route::group(['namespace' => 'Course'], function () {
                Route::patch('/courses/{course}', 'CourseController@update');
                Route::delete('/courses/{course}', 'CourseController@destroy');
            });

            Route::group(['namespace' => 'Invitation'], function () {
                Route::post('/courses/{course}/invitations', 'InvitationController@store');
            });

            Route::group(['namespace' => 'Decision'], function () {
                Route::get('/courses/{course}/tasks/{task}/decisions', 'DecisionController@index');
            });

            Route::group(['namespace' => 'File'], function () {
                Route::get('/courses/{course}/tasks/{task}/files', 'TaskFileController@index');
                Route::post('/courses/{course}/tasks/{task}/files', 'TaskFileController@store');
                Route::delete('/courses/{course}/tasks/{task}/files/{file}', 'TaskFileController@destroy');
            });

            Route::group(['namespace' => 'Setting'], function () {
                Route::post('/courses/{course}/settings/background', 'CourseSettingController@storeBackground');
                Route::get('/courses/{course}/settings/invitations', 'CourseSettingController@getInvitations');
                Route::delete('/courses/{course}/settings/background', 'CourseSettingController@DeleteBackground');
            });

            Route::group(['namespace' => 'Task'], function () {
                Route::post('/courses/{course}/tasks', 'TaskController@store');
                Route::patch('/courses/{course}/tasks/{task}', 'TaskController@update');
                Route::delete('/courses/{course}/tasks/{task}', 'TaskController@destroy');
            });
        });
    });


    // Route::group(['middleware' => 'auth:api'], function () {
    //     // Курс
    //     Route::group(['namespace' => 'Course'], function () {
    //         Route::get('/courses', 'CourseController@index'); //////
    //         Route::post('/courses', 'CourseController@store'); //////

    //         // Для участника курса (Участник и лидер)
    //         Route::group(['middleware' => 'forMembers'], function () {
    //             Route::get('/courses/{course}', 'CourseController@show'); //////

    //             Route::group(['namespace' => 'Task'], function () {
    //                 Route::get('/courses/{course}/tasks', 'TaskController@index'); //////
    //                 Route::get('/courses/{course}/tasks/{task}', 'TaskController@show'); //////

    //                 Route::group(['namespace' => 'File'], function () {
    //                     Route::get('/courses/{course}/tasks/{task}/files', 'FileController@index');
    //                 });

    //                 Route::group(['namespace' => 'Decision'], function () {

    //                     // Для создателя ответа и лидера
    //                     Route::group(['middleware' => 'creatorDecisionAndLeader'], function () {
    //                         Route::get('/courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@show');
    //                     });

    //                     // Только для создателя ответа
    //                     Route::group(['middleware' => 'creatorDecision'], function () {
    //                         Route::patch('/courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@update');
    //                         Route::delete('/courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@destroy');

    //                         Route::group(['namespace' => 'File'], function () {
    //                             Route::post('/courses/{course}/tasks/{task}/decisions/{decision}', 'FileController@store');
    //                             Route::post('/courses/{course}/tasks/{task}/decisions/{decision}/files/{file}', 'FileController@destroy');
    //                         });
    //                     });
    //                 });
    //             });
    //         });

    //         // Только для участника (не лидера) курса
    //         Route::group(['middleware' => 'onlyMember'], function () {
    //             Route::group(['namespace' => 'Task'], function () {
    //                 Route::group(['namespace' => 'Decision'], function () {
    //                     Route::post('/courses/{course}/tasks/{task}/decisions', 'DecisionController@store');
    //                 });
    //             });
    //         });

    //         // Только для лидера курса
    //         Route::group(['middleware' => 'onlyLeader'], function () {
    //             Route::patch('/courses/{course}', 'CourseController@update');
    //             Route::post('/courses/{course}/background', 'CourseController@storeBackground');
    //             Route::delete('/courses/{course}/background', 'CourseController@DeleteBackground');
    //             Route::delete('/courses/{course}', 'CourseController@destroy');

    //             Route::group(['namespace' => 'Task'], function () {
    //                 Route::post('/courses/{course}/tasks', 'TaskController@store');
    //                 Route::patch('/courses/{course}/tasks/{task}', 'TaskController@update');
    //                 Route::delete('/courses/{course}/tasks/{task}', 'TaskController@destroy');

    //                 Route::group(['namespace' => 'File'], function () {
    //                     Route::get('/courses/{course}/tasks/{task}/files', 'FileController@index');
    //                     Route::post('/courses/{course}/tasks/{task}/files', 'FileController@store');
    //                     Route::delete('/courses/{course}/tasks/{task}/files/{file}', 'FileController@destroy');
    //                 });

    //                 Route::group(['namespace' => 'Decision'], function () {
    //                     Route::get('/courses/{course}/tasks/{task}/decisions', 'DecisionController@index');
    //                 });
    //             });
    //         });
    //     });
    // });
});
