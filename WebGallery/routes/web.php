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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'HomeController@index')->name('index');

Auth::routes();

Route::get('/look', 'HomeController@look')->name('look');
Route::get('/look/{usercode}', 'HomeController@usergallery')->name('usergallery');
Route::get('/look/{usercode}/{picID}', 'HomeController@picview')->name('picview');
Route::get('/sittings', 'HomeController@sittings')->name('sittings');
Route::post('/sittings', 'HomeController@sittings')->name('sittings');
Route::get('/gallery/{galleryID}', 'HomeController@showgallery')->name('showgallery');
Route::get('/gallery', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/upload', 'HomeController@upload')->name('upload');
Route::post('/uploadfile', 'HomeController@uploadfile')->name('uploadfile');
Route::post('/updategallery', 'HomeController@updategallery')->name('updategallery');
Route::post('/updateimg', 'HomeController@updateimg')->name('updateimg');
Route::post('/editimg', 'HomeController@editimg')->name('editimg');
