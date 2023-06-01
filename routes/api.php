<?php

use App\Http\Controllers\Messenger\MessengerController;
use GuzzleHttp\Middleware;
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
            Route::post('/courses', 'CourseController@index');
            Route::post('/courses/store', 'CourseController@store');
            Route::post('/connection', 'CourseController@connection');
            Route::post('/connection/{link}', 'CourseController@connection');
        });

        Route::group(['namespace' => 'Invitation'], function () {
            Route::post('/invitations/{invitation}', 'InvitationController@accept');
            Route::get('/invitations', 'InvitationController@index');
        });

        Route::group(['namespace' => 'User'], function () {
            Route::post('/user', 'UserController@show');
            Route::patch('/user/{user}/update', 'UserController@update');
            Route::post('/user/{user}/update', 'UserController@update');
        });

        Route::group(['namespace' => 'Notification'], function () {
            Route::post('/notifications', 'NotificationController@index');
            Route::post('/notifications/default', 'NotificationController@default');
            Route::patch('/notifications/{notification}/read', 'NotificationController@read');
        });

        // --------------------------------------------------------------------------------------------

        // Для участников
        Route::group(['middleware' => 'forMembers'], function () {
            Route::group(['namespace' => 'Course'], function () {
                Route::get('/courses/{course}', 'CourseController@show');
                Route::get('/courses/{course}/members', 'CourseController@members');
                Route::post('/courses/{course}/leave', 'CourseController@leave');
            });

            Route::group(['middleware' => 'forMessengerMembers'], function () {
                Route::get('/courses/{course}/messengers', 'Messenger\MessengerController@index')->name('messenger.index');
                Route::get('/courses/{course}/messengers/{messenger}', 'Messenger\MessengerController@show');
                Route::post('/courses/{course}/messengers/{messenger}', 'Messenger\MessengerController@store');
                Route::post('/courses/{course}/messengers', 'Messenger\MessengerController@search')->name('messenger.search');
            });

            Route::group(['namespace' => 'File', 'middleware' => 'forFileMembers'], function () {
                Route::get('/courses/{course}/tasks/{task}/files', 'TaskFileController@index');
                Route::get('/courses/{course}/tasks/{task}/files/{file}', 'TaskFileController@show');
            });


            Route::group(['namespace' => 'Folder', 'middleware' => 'forFolderMembers'], function () {
                Route::get('/courses/{course}/tasks/{task}/folders/{folder}', 'FolderController@taskShow');
                Route::get('/courses/{course}/tasks/{task}/mainFolder', 'FolderController@taskMainShow')->name('folder.mainFolder');
            });

            Route::group(['namespace' => 'Task', 'middleware' => 'forTaskMembers'], function () {
                Route::post('/courses/{course}/tasks', 'TaskController@index')->name('task.index');
                Route::get('/courses/{course}/tasks/{task}', 'TaskController@show');
            });
        });

        // --------------------------------------------------------------------------------------------

        // Для создателя ответа
        Route::group(['middleware' => 'creatorDecision'], function () {
            Route::group(['namespace' => 'Course'], function () {
            });

            Route::group(['namespace' => 'Decision', 'middleware' => 'forDecisionMembers'], function () {
                Route::delete('/courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@destroy');
            });

            Route::group(['namespace' => 'File', 'middleware' => 'forFileMembers'], function () {
                Route::post('/courses/{course}/tasks/{task}/decisions/{decision}/files', 'DecisionFileController@store')->name('file.DecisionStore');
                Route::delete('/courses/{course}/tasks/{task}/decisions/{decision}/files/{file}', 'DecisionFileController@destroy');
            });

            Route::group(['namespace' => 'Folder', 'middleware' => 'forFolderMembers'], function () {
                Route::delete('/courses/{course}/tasks/{task}/decisions/{decision}/folders/{folder}', 'FolderController@DecisionFolderDestroy');
                Route::post('/courses/{course}/tasks/{task}/decisions/{decision}/folderCreate', 'FolderController@decisionStore')->name('folder.decisionStore');
            });

            Route::group(['namespace' => 'Setting'], function () {
            });

            Route::group(['namespace' => 'Task', 'middleware' => 'forTaskMembers'], function () {
            });
        });

        // --------------------------------------------------------------------------------------------

        // Для создателя ответа и лидера
        Route::group(['middleware' => 'creatorDecisionAndLeader'], function () {
            Route::group(['namespace' => 'Course'], function () {
            });

            Route::group(['namespace' => 'Decision', 'middleware' => 'forDecisionMembers'], function () {
                Route::patch('/courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@update');
                Route::get('/courses/{course}/tasks/{task}/decisions/{decision}', 'DecisionController@show');
                Route::get('/courses/{course}/tasks/{task}/authDecision', 'DecisionController@authShow')->name('decision.authShow');
            });

            Route::group(['namespace' => 'Review', 'middleware' => 'forReviewMembers'], function () {
                Route::get('/courses/{course}/tasks/{task}/decisions/{decision}/files/{file}/reviews', 'ReviewController@index');
            });

            Route::group(['namespace' => 'File', 'middleware' => 'forFileMembers'], function () {
                Route::get('/courses/{course}/tasks/{task}/decisions/{decision}/files/{file}', 'DecisionFileController@show');
            });

            Route::group(['namespace' => 'Folder', 'middleware' => 'forFolderMembers'], function () {
                Route::get('/courses/{course}/tasks/{task}/decisions/{decision}/folders/{folder}', 'FolderController@decisionShow');
            });

            Route::group(['namespace' => 'Setting'], function () {
            });

            Route::group(['namespace' => 'Task', 'middleware' => 'forTaskMembers'], function () {
            });
        });

        // --------------------------------------------------------------------------------------------

        // Не лидер
        Route::group(['middleware' => 'onlyMember'], function () {
            Route::group(['namespace' => 'Course'], function () {
            });

            Route::group(['namespace' => 'Decision', 'middleware' => 'forDecisionMembers'], function () {
                Route::post('/courses/{course}/tasks/{task}/decisions', 'DecisionController@store');
            });

            Route::group(['namespace' => 'File', 'middleware' => 'forFileMembers'], function () {
            });

            Route::group(['namespace' => 'Setting'], function () {
            });

            Route::group(['namespace' => 'Task', 'middleware' => 'forTaskMembers'], function () {
            });
        });

        // --------------------------------------------------------------------------------------------

        // Лидер
        Route::group(['middleware' => 'onlyLeader'], function () {
            Route::group(['namespace' => 'Course'], function () {
                Route::patch('/courses/{course}', 'CourseController@update');
                Route::delete('/courses/{course}', 'CourseController@destroy');
                Route::delete('/courses/{course}/users/{user}/expel', 'CourseController@expel');
            });

            Route::group(['namespace' => 'Invitation'], function () {
                Route::post('/courses/{course}/invitations', 'InvitationController@store');
            });

            Route::group(['namespace' => 'Review'], function () {
                Route::post('/courses/{course}/tasks/{task}/decisions/{decision}/files/{file}/reviews', 'ReviewController@store');
                Route::patch('/courses/{course}/tasks/{task}/decisions/{decision}/files/{file}/reviews/{review}', 'ReviewController@update');
                Route::delete('/courses/{course}/tasks/{task}/decisions/{decision}/files/{file}/reviews/{review}', 'ReviewController@destroy');
            });

            Route::group(['namespace' => 'Decision', 'middleware' => 'forDecisionMembers'], function () {
                Route::get('/courses/{course}/tasks/{task}/decisions', 'DecisionController@index');
            });

            Route::group(['namespace' => 'File', 'middleware' => 'forFileMembers'], function () {
                // Route::get('/courses/{course}/tasks/{task}/files', 'TaskFileController@index')->name('file.index');
                Route::post('/courses/{course}/tasks/{task}/files', 'TaskFileController@store')->name('file.store');
                // Route::post('/files', 'TaskFileController@store')->name('file.store');
                Route::delete('/courses/{course}/tasks/{task}/files/{file}', 'TaskFileController@destroy');
            });

            Route::group(['namespace' => 'Folder', 'middleware' => 'forFolderMembers'], function () {
                Route::delete('/courses/{course}/tasks/{task}/folders/{folder}', 'FolderController@TaskFolderDestroy');
                Route::post('/courses/{course}/tasks/{task}/folderCreate', 'FolderController@taskStore')->name('folder.taskStore');
            });

            Route::group(['namespace' => 'Setting'], function () {
                Route::post('/courses/{course}/settings/background', 'CourseSettingController@storeBackground');
                Route::get('/courses/{course}/settings/invitations', 'CourseSettingController@getInvitations');
                Route::delete('/courses/{course}/settings/background', 'CourseSettingController@DeleteBackground');
                Route::patch('/courses/{course}/settings/code', 'CourseSettingController@changeCode');
                Route::patch('/courses/{course}/settings/link', 'CourseSettingController@changeLink');
            });

            Route::group(['namespace' => 'Task', 'middleware' => 'forTaskMembers'], function () {
                Route::patch('/courses/{course}/tasks/{task}/published', 'TaskController@published');
                Route::post('/courses/{course}/tasks/billet', 'TaskController@billet')->name('task.billet');
                Route::post('/courses/{course}/tasks/store', 'TaskController@store')->name('task.store');
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
