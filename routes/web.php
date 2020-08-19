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

Route::pattern('id', '[0-9]+');

Auth::routes();

//ajax validate
Route::post('/register-validate', 'Auth\RegisterController@registerPost');
Route::post('/login-validate', 'Auth\LoginController@loginPost');

//ajax update
Route::post('/getMessUpdate', 'Frontend\HomeController@getMessUpdate');
//ajax loading messages on scroll
Route::post('/loadMessages', 'Frontend\HomeController@loadMessages');


Route::get('/', 'Frontend\HomeController@index');
Route::get('/chat/{id}', 'Frontend\HomeController@getChat');

Route::post('/sendMessage', 'Frontend\HomeController@sendMessage');



