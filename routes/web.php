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

Route::get('dang-nhap', 'Frontend\AuthController@loginView');
Route::post('login', 'Frontend\AuthController@login');
Route::get('logout', 'Frontend\AuthController@logout');

Route::group(['prefix' => 'admin', 'middleware' => ['auth.admin']], function () {
    Route::get('/', 'Backend\MainController@index');

    Route::get('users', 'Backend\UserController@index');
    Route::get('userAttribute.data', 'Backend\UserController@userAttribute');
    Route::get('users/create', 'Backend\UserController@create');
    Route::post('users/store', 'Backend\UserController@store');

    Route::get('users/edit/{id}', 'Backend\UserController@edit');
    Route::post('users/update', 'Backend\UserController@update');
});