<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', function () 
    {
    	return view('welcome');
	});

    /*=============================================
    =            Projects Route block            =
    =============================================*/
    Route::get('/projects',             'ProjectController@index');
    Route::get('/projects/{id}',        'ProjectController@show');
    Route::post('/new-project',         'ProjectController@store');
    Route::post('/get-projects',        'ProjectController@getProjects');
    Route::post('/edit-project/{id}',   'ProjectController@update');
    /*=====  End of Section comment block  ======*/
    
    /*=========================================
    =            Tasks Route block            =
    =========================================*/
    Route::get('/project-tasks/{id}',    'TaskController@showTasks');
    Route::post('/get-project-tasks/',   'TaskController@getTasks');
    Route::post('/new-task',             'TaskController@store');
    Route::get('/tasks/{id}',            'TaskController@show');
    Route::post('/edit-task/{id}',       'TaskController@update');
    /*=====  End of Tasks Route block  ======*/
    



    Route::get('/test/{id}', 'ProjectController@exportExcel');

    Route::get('/home', 'HomeController@index');
});
