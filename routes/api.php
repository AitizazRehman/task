<?php

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

Route::group(['middleware' => ['guest:api']], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('login/refresh', 'Auth\LoginController@refresh');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('register', 'Auth\RegisterController@register');
});

Route::group(['middleware' => ['jwt']], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('me', 'Auth\LoginController@me');
    Route::put('profile', 'ProfileController@update');

    Route::post('task/save-task', 'TaskController@store');
    Route::get('task/get-tasks', 'TaskController@index');
    Route::post('task/update-task', 'TaskController@update');
    Route::post('task/delete-task', 'TaskController@destroy');
});
