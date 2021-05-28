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

// User Login, Registration & Logout
Route::group(['prefix' => 'auth'], function(){
	Route::post('login','Api\UserController@login');   
	Route::post('register','Api\UserController@register');
	Route::get('logout', 'Api\UserController@logout')->middleware('auth:api');
});

// Project
Route::group(['prefix' => 'projects', 'middleware' => ['auth:api']], function(){
	Route::get('/', 'Api\ProjectsController@index');
	Route::post('/add','Api\ProjectsController@store');
	Route::patch('/{id}/edit','Api\ProjectsController@update');
	Route::get('/{id}/view', 'Api\ProjectsController@show');
	Route::delete('/{id}/delete', 'Api\ProjectsController@destroy');
});

Route::group(['prefix' => 'jobs'], function(){
	Route::delete('delete', 'Api\ProjectsController@removeRecords');
	Route::patch('update', 'Api\ProjectsController@updateStatus');
});

Route::group(['prefix' => 'tasks', 'middleware' => ['auth:api']], function(){
	Route::get('/', 'Api\TasksController@index');
	Route::post('/create','Api\TasksController@create');
	Route::post('/{id}/assign','Api\TasksController@store');
	Route::patch('/{id}/edit','Api\TasksController@update');
	Route::get('/{id}/view', 'Api\TasksController@show');
	Route::delete('/{id}/delete', 'Api\TasksController@destroy');
});
