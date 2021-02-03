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

////////////////////////////////////////////////////////////////////////////////////////////

	// approve device certificate
	Route::post('approve-device-certificate', 'TransmissionDataDeviceController@approveDeviceCertificate')->name('approve-device-certificate');

	// approve grand father device certificate
	Route::post('approve-device-grandfather-certificate', 'TransmissionDataDeviceController@approveGrandFatherDeviceCertificate')->name('approve-device-grandfather-certificate');

	// approve photographer certificate
	Route::post('approve-photographer-certificate', 'TransmissionDataPhotographerController@approvePhotographerCertificate')->name('approve-photographer-certificate');

	// approve photographer provisional certificate
	Route::post('approve-photographer-provisional-certificate', 'TransmissionDataPhotographerController@approvePhotographerProvisionalCertificate')->name('approve-photographer-provisional-certificate');


Route::group(['middleware' => ['auth', 'web', 'roles']], function () {

	// get certification template
	Route::get('certification-template', 'CertificationPreferencesController@getTemplate')->name('certification-template'); ///

// save certification template
	Route::post('certification-template', 'CertificationPreferencesController@saveTemplate')->name('save-certification-template'); ///

// get template data ajax
	Route::get('get-template-data', 'CertificationPreferencesController@getTemplateData')->name('get-template-data'); ///

// update certification template
	Route::post('update-certification-template', 'CertificationPreferencesController@updateTemplate')->name('update-certification-template'); ////

// get preferences assign modality
	Route::get('preferences/assign-modality/{study_id}', 'CertificationPreferencesController@assignModality')->name('preferences.assign-modality'); ///

// post preferences assign modality
	Route::post('preferences/save-assign-modality/{study_id}', 'CertificationPreferencesController@saveAssignModality')->name('preferences.save-assign-modality'); ///

// post preferences remove modality
	Route::post('preferences/remove-assign-modality/{study_id}', 'CertificationPreferencesController@removeAssignModality')->name('preferences.remove-assign-modality'); ///

// get preferences assign devices
	Route::get('preferences/assign-device/{study_id}', 'CertificationPreferencesController@assignDevice')->name('preferences.assign-device');

// post preferences assign device
	Route::post('preferences/save-assign-device/{study_id}', 'CertificationPreferencesController@saveAssignDevice')->name('preferences.save-assign-device'); ///

// post preferences remove device
	Route::post('preferences/remove-assign-device/{study_id}', 'CertificationPreferencesController@removeAssignDevice')->name('preferences.remove-assign-device'); ///

// get preferences study setup
	Route::get('preferences/study-setup/{study_id}', 'CertificationPreferencesController@studySetup')->name('preferences.study-setup'); ////

// save preferences study setup
	Route::post('preferences/study-setup/{study_id}', 'CertificationPreferencesController@saveStudySetup')->name('preferences.save-study-setup'); ///

// get studies for preference
	Route::resource('certification-preferences', 'CertificationPreferencesController'); ///

// certificate device
    Route::resource('certification-device', 'TransmissionDataDeviceController'); ///

// certificate photographer
    Route::resource('certification-photographer', 'TransmissionDataPhotographerController'); ///

// generate photographer certificate
	Route::post('generate-photographer-certificate', 'TransmissionDataPhotographerController@generatePhotographerCertificate')->name('generate-photographer-certificate'); ///

// update photographer provisional certificate
	Route::post('update-photographer-provisonal-certificate', 'TransmissionDataPhotographerController@updatePhotographerProvisonalCertificate')->name('update-photographer-provisonal-certificate'); ///

// generate device certificate
	Route::post('generate-device-certificate', 'TransmissionDataDeviceController@generateDeviceCertificate')->name('generate-device-certificate'); ///

// update device provisional certificate
	Route::post('update-device-provisonal-certificate', 'TransmissionDataDeviceController@updateDeviceProvisonalCertificate')->name('update-device-provisonal-certificate'); ///

// update photographer transmission status
    // Route::post('update-photographer-transmission-status', 'TransmissionDataPhotographerController@updatePhotographerTransmissionStatus')->name('update-photographer-transmission-status');

// get study setup email ajax
	Route::get('get-study-setup-emails', 'TransmissionDataPhotographerController@getStudySetupEmail')->name('get-study-setup-emails'); ///

// get transmission data for Certification geneartion ajax
	Route::get('get-transmission-data', 'TransmissionDataPhotographerController@getTransmissionData')->name('get-transmission-data'); ///

// archive photographer transmission
	Route::get('archive-photographer-transmission/{transmission_id}/{status}', 'TransmissionDataPhotographerController@archivePhotographerTransmission')->name('archive-photographer-transmission'); ///

// archive device transmission
	Route::get('archive-device-transmission/{transmission_id}/{status}', 'TransmissionDataDeviceController@archiveDeviceTransmission')->name('archive-device-transmission'); ///

// certified Photographer
	Route::get('certified-photographer', 'TransmissionDataPhotographerController@certifiedPhotographer')->name('certified-photographer'); ///

// check Photographer/device Grandfather Certificate
	Route::get('check-grandfather-certificate', 'TransmissionDataPhotographerController@checkGrandfatherCertificate')->name('check-grandfather-certificate'); ///

// generate photographer grandfather certificate
	Route::post('generate-photographer-grandfather-certificate', 'TransmissionDataPhotographerController@generatePhotographerGrandfatherCertificate')->name('generate-photographer-grandfather-certificate'); ///

// certified Device
	Route::get('certified-device', 'TransmissionDataDeviceController@certifiedDevice')->name('certified-device'); ///

// generate device grandfather certificate
	Route::post('generate-device-grandfather-certificate', 'TransmissionDataDeviceController@generateDeviceGrandfatherCertificate')->name('generate-device-grandfather-certificate'); ///

// Archive Device Transmission
	Route::get('archived-device-transmission-listing', 'TransmissionDataDeviceController@getArchivedDeviceTransmissionListing')->name('archived-device-transmission-listing'); ///

// Archive Photographer Transmission
	Route::get('archived-photographer-transmission-listing', 'TransmissionDataPhotographerController@getArchivedPhotographerTransmissionListing')->name('archived-photographer-transmission-listing'); ///

// change certificate status for both photographer and device
	Route::post('change-certificate-status', 'TransmissionDataPhotographerController@changeCertificateStatus')->name('change-certificate-status'); ///

// change certificate expiry date for photographer/ device
	Route::post('change-certificate-date', 'TransmissionDataPhotographerController@changeCertificateDate')->name('change-certificate-date'); ///

// display photographer certificate PDF
	Route::get('photographer-certificate-pdf/{file_name}', function($fileName) {

		$path = storage_path('certificates_pdf/photographer/'.$fileName);

		return Response::make(file_get_contents($path), 200, [

		    'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'inline; filename="'.$fileName.'"'

		]);

	})->name('photographer-certificate-pdf'); ///

// display device certificate PDF
	Route::get('device-certificate-pdf/{file_name}', function($fileName) {

		$path = storage_path('certificates_pdf/device/'.$fileName);

		return Response::make(file_get_contents($path), 200, [

		    'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'inline; filename="'.$fileName.'"'

		]);

	})->name('device-certificate-pdf'); ///

	// show user signature
	Route::get('user-signature/{file_name}', function($fileName) {
		
		$decryptFileName = decrypt($fileName);
		$path = storage_path('user_signature/'.$decryptFileName);
	    if (!File::exists($path)) {

	        abort(404);

	    }

	    $file = File::get($path);
	    $type = File::mimeType($path);
	    $response = Response::make(decrypt($file), 200);
	    $response->header("Content-Type", $type);
	    return $response;

	})->name('user-signature'); ///
    
});