<?php

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

Route::post('/insert-post', 'PostController@InsertPost');
Route::get('/all-posts', 'PostController@AllPost')->name('all.post');

Route::get('/edit-post/{id}', 'PostController@EditPost');
Route::post('/update-post/{id}', 'PostController@UpdatePost');
Route::get('/delete-post/{id}', 'PostController@DeletePost');

//Password.....
Route::get('password-change', 'HomeController@PassChange')->name('pass.change');
Route::post('/password-update', 'HomeController@PassUpdate');

Route::get('/news-add', 'PostController@NewsAdd')->name('news.add');
Route::post('insert-news', 'PostController@InsertNews');
Route::get('/all-news', 'PostController@AllNews')->name('all.news');
Route::get('/delete-news/{id}', 'PostController@DeleteNews');