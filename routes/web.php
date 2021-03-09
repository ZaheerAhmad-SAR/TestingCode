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

Route::get('/2fa/enable', 'Google2FAController@enableTwoFactor');
Route::get('/2fa/disable', 'Google2FAController@disableTwoFactor');
Route::get('/2fa/validate', 'Auth\LoginController@getValidateToken');
Route::post('/2fa/validate', ['middleware' => 'throttle:5', 'uses' => 'Auth\LoginController@postValidateToken']);
Route::POST('/registration', 'Auth\RegisterController@register')->name('accept');
Auth::routes(['register' => false]);
Route::get('/home', 'HomeController@index')->name('home');
Route::get('home/working_status', 'HomeController@working_status')->name('study.workingStatus');
Route::get('home/update_online_at_time', 'HomeController@update_online_at_time')->name('study.updateOnlineTime');
Route::get('/vtag', 'VtagCOntroller@index')->name('vtag');
// For Users prefrences

Route::get('home/user_preferences', 'HomeController@user_preferences')->name('home.user-preferences');
Route::post('home/update_user_prefrences', 'HomeController@update_user_prefrences')->name('home.UpdateUserPrefrences');
