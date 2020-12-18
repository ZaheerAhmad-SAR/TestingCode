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

// get template data ajax
	Route::get('get-template-data', 'CertificationPreferencesController@getTemplateData')->name('get-template-data');

// update certification template
	Route::post('update-certification-template', 'CertificationPreferencesController@updateTemplate')->name('update-certification-template');

// get preferences assign modality
	Route::get('preferences/assign-modality/{study_id}', 'CertificationPreferencesController@assignModality')->name('preferences.assign-modality');

// post preferences assign modality
	Route::post('preferences/save-assign-modality/{study_id}', 'CertificationPreferencesController@saveAssignModality')->name('preferences.save-assign-modality');

// post preferences remove modality
	Route::post('preferences/remove-assign-modality/{study_id}', 'CertificationPreferencesController@removeAssignModality')->name('preferences.remove-assign-modality');

// get preferences assign devices
	Route::get('preferences/assign-device/{study_id}', 'CertificationPreferencesController@assignDevice')->name('preferences.assign-device');

// post preferences assign device
	Route::post('preferences/save-assign-device/{study_id}', 'CertificationPreferencesController@saveAssignDevice')->name('preferences.save-assign-device');

// post preferences remove device
	Route::post('preferences/remove-assign-device/{study_id}', 'CertificationPreferencesController@removeAssignDevice')->name('preferences.remove-assign-device');

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

// update photographer transmission status
    // Route::post('update-photographer-transmission-status', 'TransmissionDataPhotographerController@updatePhotographerTransmissionStatus')->name('update-photographer-transmission-status');

// get study setup email ajax
	Route::get('get-study-setup-emails', 'TransmissionDataPhotographerController@getStudySetupEmail')->name('get-study-setup-emails');

// get transmission data for Certification geneartion ajax
	Route::get('get-transmission-data', 'TransmissionDataPhotographerController@getTransmissionData')->name('get-transmission-data');


Route::group(['middleware' => ['auth', 'web']], function () {
    
});
