<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/me', function (Request $request) {
    $user = [
        'id'         => $request->user()->id,
        'email'      => $request->user()->email,
        'name'       => $request->user()->name,
        'created_at' => $request->user()->created_at,
        'bio'        => $request->user()->bio,
        'avatar'     => url('/') . '/uploads/' . $request->user()->avatar,
        'cover'      => url('/') . '/uploads/' . $request->user()->cover,
        'role'       => $request->user()->getRoleNames(),
    ];
    return response()->json($user, 200);
});

Route::post('/login', 'UserController@login');

Route::middleware('auth:api')->group(function () {    
       
    Route::get('/logout', 'UserController@logout');

    Route::get('/home-statistics', 'HomeController@homeStatistics');

    Route::post('/user-update/{userId}', 'UserController@update');
    Route::post('/user-update-password/{userId}', 'UserController@updatePassword');
    Route::post('/user-update-avatar/{userId}', 'UserController@updateAvatar');
    Route::post('/user-update-cover/{userId}', 'UserController@updateCover');

    Route::get('/projects', 'ProjectController@list');
    Route::get('/project/{id}', 'ProjectController@show');

    Route::group(['middleware' => ['role:administrator']], function () {
        Route::post('/register', 'UserController@register');
        Route::post('/assign-role', 'UserController@assignRole');
        Route::post('/give-permission', 'UserController@givePermission');
    });
    
    Route::group(['middleware' => ['permission:create project|edit project|delete project']], function () {
        //project CRUD
        Route::post('/project/create', 'ProjectController@create');
        Route::post('/project/edit/{id}', 'ProjectController@edit');
        Route::get('/project/delete/{id}', 'ProjectController@delete');        
    });

    Route::group(['middleware' => ['permission:create sprint|edit sprint|delete sprint']], function () {
        //sprint CRUD
        Route::post('/sprint/create', 'SprintController@create');
        Route::post('/sprint/edit/{id}', 'SprintController@edit');
        Route::get('/sprint/delete/{id}', 'SprintController@delete');
        Route::get('/sprint/{id}', 'SprintController@show');
    });

    Route::group(['middleware' => ['permission:create issue|edit issue|delete issue']], function () {
        //issue CRUD
        Route::post('/issue/create', 'IssueController@create');
        Route::post('/issue/edit/{id}', 'IssueController@edit');
        Route::get('/issue/delete/{id}', 'IssueController@delete');
    });

    Route::group(['middleware' => ['permission:create task|edit task|delete task']], function () {
        //task CRUD
        Route::post('/task/create', 'TaskController@create');
        Route::post('/task/edit/{id}', 'TaskController@edit');
        Route::get('/task/delete/{id}', 'TaskController@delete');
    });

});
