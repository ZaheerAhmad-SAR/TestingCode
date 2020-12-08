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

// post device transmission
	Route::post('transmissions/transmissionDataDevice', 'TransmissionDataDeviceController@transmissionDataDevice')->name('transmissions.transmissionDataDevice');

// post photographer transmission
	Route::post('transmissions/transmissionDataPhotographer', 'TransmissionDataPhotographerController@transmissionDataPhotographer')->name('transmissions.transmissionDataPhotographer');

// certificate device
    Route::resource('certification-device', 'TransmissionDataDeviceController');

// certificate photographer
    Route::resource('certification-photographer', 'TransmissionDataPhotographerController');


Route::group(['middleware' => ['auth', 'web']], function () {
    
});
