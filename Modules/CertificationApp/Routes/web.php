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

// test post phographer transmission
	Route::post('transmissions/testTransmissionDataPhotographer', 'TransmissionDataPhotographerController@testTransmissionDataPhotographer')->name('transmissions.testTransmissionDataPhotographer');

// post device transmission
	Route::post('transmissions/transmissionDataDevice', 'TransmissionDataDeviceController@transmissionDataDevice')->name('transmissions.transmissionDataDevice');

// post photographer transmission
	Route::post('transmissions/transmissionDataPhotographer', 'TransmissionDataPhotographerController@transmissionDataPhotographer')->name('transmissions.transmissionDataPhotographer');

// get certification template
	Route::get('certification-template', 'CertificationPreferencesController@getTemplate')->name('certification-template');

// save certification template
	Route::post('certification-template', 'CertificationPreferencesController@saveTemplate')->name('save-certification-template');

// update certification template
	Route::post('update-certification-template', 'CertificationPreferencesController@updateTemplate')->name('update-certification-template');

// get preferences assign modality
	Route::get('preferences/assign-modality/{study_id}', 'CertificationPreferencesController@assignModality')->name('preferences.assign-modality');

// post preferences assign modality
	Route::post('preferences/save-assign-modality/{study_id}', 'CertificationPreferencesController@saveAssignModality')->name('preferences.save-assign-modality');

// post preferences remove modality
	Route::post('preferences/remove-assign-modality/{study_id}', 'CertificationPreferencesController@removeAssignModality')->name('preferences.remove-assign-modality');

// get preferences study setup
	Route::get('preferences/study-setup/{study_id}', 'CertificationPreferencesController@studySetup')->name('preferences.study-setup');

// save preferences study setup
	Route::post('preferences/study-setup/{study_id}', 'CertificationPreferencesController@saveStudySetup')->name('preferences.save-study-setup');

// get studies for preference
	Route::resource('certification-preferences', 'CertificationPreferencesController');

// certificate device
    Route::resource('certification-device', 'TransmissionDataDeviceController');

// certificate photographer
    Route::resource('certification-photographer', 'TransmissionDataPhotographerController');


Route::group(['middleware' => ['auth', 'web']], function () {
    
});
