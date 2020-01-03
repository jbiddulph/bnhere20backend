<?php

use Illuminate\Http\Request;

Route::group([

    'middleware' => 'api'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('sendPasswordResetLink','ResetPasswordController@sendEmail');
    Route::post('resetPassword','ChangePasswordController@process');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// List articles
Route::get('articles', 'ArticleController@index');
//List single article
Route::get('article/{id}', 'ArticleController@show');
//Create New Article
Route::post('article', 'ArticleController@store');
//Update Article
Route::put('article', 'ArticleController@store');
//Delete Article
Route::delete('article/{id}', 'ArticleController@destroy');

