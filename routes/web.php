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


Auth::routes();
Route::resource('post', 'PostController')->except(['index','update','destroy', 'show','edit']);
Route::get('/', 'HomeController@index')->name('home');
Route::get('page/{id}', 'PageController@show');
Route::get('post/{id}','PostController@show');
//Route::get('post/create','PostController@create');
