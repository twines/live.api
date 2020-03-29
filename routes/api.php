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
Route::group(['namespace' => 'v1', 'prefix' => 'v1'], function () {
    Route::post('/login', 'LoginController@login');
    Route::post('/logout', 'LoginController@logout');
    Route::post('/refresh', 'LoginController@refresh');
    Route::post('/register', 'RegisterController@register');
    Route::post('/upload', 'UploadController@index');
    Route::group(['middleware' => ['jwt.auth']], function () {
        //用户认证
        Route::post('/user/auth', 'UserController@doAuth');
    });
});

