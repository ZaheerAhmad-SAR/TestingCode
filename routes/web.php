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
    return view('auth.login');
});

    Route::get('/2fa', 'TwoFactorController@show2faForm');
    Route::post('/2fa', 'TwoFactorController@sendToken');
    Route::post('/2fa_verify', 'TwoFactorController@verfiyToken');
    Route::get('/2f_login/{token}',function ()
    {
        return view('2f_login');
    });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');




