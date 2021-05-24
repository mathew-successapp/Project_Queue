<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/posts/{post}', 'HomeController@show');

/*Route::get('/post/{post}', function($post){
	$posts = ['post1' => 'post new', 'post2' => 'post new tec'];

	if(!in_array($post, $posts)){
		abort(404, 'Not available using in_array');
	}
	/*if(!array_key_exists($post, $posts)){
		abort(404, "Post is not available");
	}*/
/*
	return $posts[$post];
});*/
