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
/*
Route::get('/', function () {
    return view('welcome');
});
//*/

Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');
Route::get('/services', 'PagesController@services');
//*/

//echo "Tracer 1";

Route::get('/users/{id}', function($id) {
    return 'This is a user ' . $id;
});

Auth::routes();
//echo "Tracer 2";

Route::get('/home', 'HomeController@index')->name('home');
//echo "Tracer 3";

Route::resource('posts', 'PostsController');

//echo "Tracer 4";
