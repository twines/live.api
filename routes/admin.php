<?php
/**
 * Created by 莘莘
 * User: 寒云
 * Email: 1355081829@qq.com
 * Date: 2020/3/27
 * Time: 20:47
 */
Route::group(['namespace' => 'v1', 'prefix' => 'v1'], function () {
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout');
    Route::post('refresh', 'LoginController@refresh');
    Route::group(['middleware' => ['admin']], function () {
        Route::get('/role/permission/{roleId}', 'RoleController@getPermissionList');
        Route::get('/dashboard', 'DashboardController@index');
        Route::get('/user/list', 'UserController@getUserList');
        Route::post('/user/add', 'UserController@addUser');
        Route::delete('/user/{userId}', 'UserController@getUserList');
        Route::get('/user/{userId}', 'UserController@getUserDetail');
        Route::put('/user/{userId}', 'UserController@updateUser');
    });
});
