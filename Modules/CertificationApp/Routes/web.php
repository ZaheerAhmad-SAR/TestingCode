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

// certification template
	Route::get('certification-template', 'CertificationPreferencesController@getTemplate')->name('certification-template');

// save certification template
	Route::post('certification-template', 'CertificationPreferencesController@saveTemplate')->name('save-certification-template');

// save certification template
	Route::post('update-certification-template', 'CertificationPreferencesController@updateTemplate')->name('update-certification-template');

// get preferences assign modality
	Route::get('preferences/assign-modality/{study_id}', 'CertificationPreferencesController@assignModality')->name('preferences.assign-modality');

// post preferences assign modality
	Route::post('preferences/save-assign-modality/{study_id}', 'CertificationPreferencesController@saveAssignModality')->name('preferences.save-assign-modality');

// get studies for preference
	Route::resource('certification-preferences', 'CertificationPreferencesController');

// certificate device
    Route::resource('certification-device', 'TransmissionDataDeviceController');

// certificate photographer
    Route::resource('certification-photographer', 'TransmissionDataPhotographerController');


Route::group(['middleware' => ['auth', 'web']], function () {
    
});
