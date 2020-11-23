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
Route::group(['middleware' => ['auth', 'web']], function () {
    //Route::get('/', 'CertificationController@index');
    Route::resource('photographer', 'PhotographersControllers');
    Route::resource('devices_certify', 'DevicesController');
});
// Route::prefix('certification')->group(function() {
//     Route::get('/', 'CertificationController@index');
// });
