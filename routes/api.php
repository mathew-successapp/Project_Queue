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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// User Login & Registration
Route::post('login','Api\UserController@login');   
Route::post('register','Api\UserController@register');

// Logout 
Route::get('logout', 'Api\UserController@logout')->middleware('auth:api');

// Project
Route::get('projects', 'Api\ProjectsController@index')->middleware('auth:api');
Route::post('add_project','Api\ProjectsController@store')->middleware('auth:api');
Route::post('update_project','Api\ProjectsController@update')->middleware('auth:api');
Route::get('view_project/{id}', 'Api\ProjectsController@show')->middleware('auth:api');
Route::get('delete_project/{id}', 'Api\ProjectsController@destroy')->middleware('auth:api');

Route::get('remove_records', 'Api\ProjectsController@removeRecords')->middleware('auth:api');
Route::get('update_status', 'Api\ProjectsController@updateStatus');

Route::get('tasks', 'Api\TasksController@index')->middleware('auth:api');
Route::post('assign_task','Api\TasksController@store')->middleware('auth:api');
Route::post('update_task','Api\TasksController@update')->middleware('auth:api');
Route::get('view_task/{id}', 'Api\TasksController@show')->middleware('auth:api');
Route::get('delete_task/{id}', 'Api\TasksController@destroy')->middleware('auth:api');
