<?php

use Illuminate\Http\Request;

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

Route::post('/auth', "Api\AuthController@authenticate");

Route::middleware(['auth:airlock'])->namespace('Api')->group(function()
{
    Route::get('/users',"UserController@index");
    Route::post('/users',"UserController@store");
    Route::put('/users/{id}',"UserController@update");
    Route::get('/users/{id}',"UserController@show");
    Route::delete('/users/{id}',"UserController@delete");

    Route::get('/permissions',"PermissionController@index");
    Route::post('/permissions',"PermissionController@store");
    Route::put('/permissions/{id}',"PermissionController@update");
    Route::get('/permissions/{id}',"PermissionController@show");
    Route::delete('/permissions/{id}',"PermissionController@delete");
});