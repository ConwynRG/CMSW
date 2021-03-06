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
Route::resource('post', 'PostController')->except(['index', 'destroy', 'show']);
Route::get('/', 'HomeController@index')->name('home');
Route::get('page/{id}', 'PageController@show');
Route::get('post/{id}','PostController@show');
Route::delete('post/{id}/delete','PostController@destroy');
Route::post('comment/add', 'CommentController@addComment');
Route::post('comment/delete', 'CommentController@deleteComment');
Route::post('post/review/add', 'PostReviewController@addPostReview');
Route::post('post/review/nullify', 'PostReviewController@nullifyPostReview');
Route::get('settings','SettingsController@viewSettings');
Route::put('settings/{id}','SettingsController@updateSettings');
Route::get('lang/{locale}','LanguageController');
Route::post('/sort','HomeController@sortPosts');
