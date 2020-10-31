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

//dd(App::environment());

// test transmission view
Route::get('transmissions/transmissionData', function () {
    return view('admin::test_transmission_api');
});
// transmission end point
Route::post('transmissions/transmissionData', 'TransmissionController@transmissionData')->name('transmissions.transmissionData');

// transmissions routes
Route::resource('transmissions', 'TransmissionController');

// get study vice transmissions
Route::get('study-transmissions', 'TransmissionController@studyTransmissions')->name('transmissions.study-transmissions');

Route::post('transmissions/getAllPIBySiteId', 'TransmissionController@getAllPIBySiteId')->name('transmissions.getAllPIBySiteId');


Route::post('transmissions/queryTransmissionMail', 'TransmissionController@queryTransmissionMail')->name('transmissions.queryTransmissionMail');

Route::post('transmissions-status', 'TransmissionController@transmissionStatus')->name('transmissions-status');

Route::prefix('admin')->group(function () {
    Route::get('/', 'AdminController@index');
});
Route::group(['middleware' => ['auth', 'web']], function () {
    Route::resource('studies', 'StudyController');
    Route::get('get_steps', 'StudyStructureController@get_steps')->name('study.getSteps');
    Route::get('study_phases', 'StudyStructureController@getallphases')->name('getPhases');
    Route::get('forms/get_phases/{id}', 'FormController@get_phases')->name('forms.get_phases');
    Route::post('study/update', 'StudyStructureController@update')->name('study.updatePhase');
    // for clone steps
    Route::resource('cloneSteps', 'CloneStepsController');
    Route::post('clone_steps', 'CloneStepsController@clone_steps')->name('cloneSteps.cloneSteps');
    // for steps
    Route::DELETE('steps/delete_steps/{step_id}', 'StudyStructureController@destroySteps')->name('steps.deleteSteps');
    Route::post('steps/store_steps', 'StudyStructureController@store_steps')->name('steps.save');
    Route::post('steps/updateSteps', 'StudyStructureController@update_steps')->name('steps.update');
    // for Section
    Route::resource('sections', 'SectionController');
    Route::post('section', 'SectionController@getSectionby_id')->name('section.getSections');
    Route::post('section/update', 'SectionController@update')->name('section.updateSections');
    /// for form management
    Route::get('forms/step_by_phaseId/{id}', 'FormController@get_steps_by_phaseId')->name('forms.stepbyphaseId');
    Route::resource('forms', 'FormController');
    Route::post('forms/add_questions', 'FormController@add_questions')->name('forms.addQuestions');
    Route::post('forms/updateQuestion', 'FormController@update_questions')->name('forms.updateQuestion');
    Route::get('forms/sections_against_step/{id}', 'FormController@get_sections_against_step')->name('forms.sections_against_step');
    // skip logic
    Route::get('forms/sections_for_skip_logic/{id}', 'FormController@sections_skip_logic')->name('forms.sectionsSkip');
    Route::get('forms/sections_for_skip_logic_deactivate/{id}', 'FormController@sections_skip_logic_deactivate')->name('forms.sectionsSkipdeactivate');
    Route::get('forms/questions_for_skip_logic/{id}', 'FormController@questions_skip_logic')->name('forms.questionsSkip');
    Route::get('forms/questions_for_skip_logic_deactivate/{id}', 'FormController@questions_skip_logic_deactivate')->name('forms.questionsSkipdeactivate');
    Route::post('forms/add_skip_logic', 'FormController@add_skipLogic')->name('forms.apply_skip_logic');
    Route::post('forms/steps_to_skip', 'FormController@getSteps_toskip')->name('forms.get_steps_skip_logic');
    Route::get('forms/skip_logic/{id}', 'FormController@skip_question_on_click')->name('forms.skipLogic');
    // skip logic
    Route::get('forms/sections_by_stepId/{id}', 'FormController@get_section_by_stepId')->name('forms.sectionsbystepId');
    Route::post('studyStatus', 'StudyController@studyStatus')->name('study.studyStatus');
    Route::post('changeStatus/{id}', 'StudyController@changeStatus')->name('studies.changeStatus');
    Route::get('forms/get_Questions/{id}', 'FormController@get_Questions')->name('forms.get_Questions');
    Route::get('forms/get_allQuestions/{id}', 'FormController@get_allQuestions')->name('forms.get_allQuestions');
    Route::get('forms/changeSort/{id}', 'FormController@updateQustionsort')->name('forms.changeSort');
    Route::DELETE('forms/delete/{id}', 'FormController@deleteQuestion')->name('forms.delete');


    //routes for options groups
    //routes for options groups
    Route::resource('optionsGroup', 'OptionsGroupController');
    Route::post('optionsGroup/update', 'OptionsGroupController@update')->name('optionsGroup.update');
    Route::DELETE('optionsGroup/destroy/{options_id}', 'OptionsGroupController@destroy')->name('optionsGroup.destroy');
    Route::post('getall_options', 'FormController@getall_options')->name('getall_options');
    // routes for annotation
    Route::resource('annotation', 'AnnotationController');
    Route::post('annotation/updateAnnotation', 'AnnotationController@update_annotation')->name('annotation.updateAnnotation');
    Route::DELETE('annotation/delete/{id}', 'AnnotationController@deleteAnnotation')->name('annotation.delete');
    Route::get('annotation/get_allAnnotations/{id}', 'AnnotationController@get_allAnnotations')->name('annotation.get_allAnnotations');
});
Route::group(['middleware' => ['auth', 'web', 'roles'], 'roles' => ['admin']], function () {

    Route::resource('sites', 'SiteController');
    Route::post('sites/update', 'SiteController@update')->name('sites.updateSites');
    Route::DELETE('sites/destroy/{sites_id}', 'SiteController@destroy')->name('sites.destroy');


    Route::post('studies/update_studies', 'StudyController@update_studies')->name('studies.update_studies');
    Route::resource('devices', 'DeviceController');
    Route::resource('modalities', 'ModilityController');
    Route::resource('diseaseCohort', 'DiseaseCohortController');
    Route::get('device/{id}', 'DeviceController@getModal');


    Route::post('modalities/update', 'ModilityController@update')->name('modalities.update');

    Route::resource('subjects', 'SubjectController');

    //Route::resource('studyrole','StudyRoleController');

    Route::resource('others', 'OtherController');

    Route::get('others/{id}/showOtherBySiteId', 'OtherController@showOtherBySiteId')->name('others.showOtherBySiteId');

    Route::post('others/update', 'OtherController@update')->name('others.update');


    // routes for form managment

    //end
    // routes for study managment
    Route::resource('study', 'StudyStructureController');


    Route::post('studies/studyStatus', 'StudyController@studyStatus')->name('studies.studyStatus');
    Route::post('studies/cloneStudy', 'StudyController@cloneStudy')->name('studies.cloneStudy');

    //end
    // routes for adding sections
    // Route::resource('section','SectionController');

    //end

    Route::resource('childmodilities', 'ChildModilitiesController');

    Route::post('childmodilities/update', 'ChildModilitiesController@update')->name('childmodilities.update');

    Route::get('modalities/{id}/childshow', 'ModilityController@child')->name('modalities.childshow');

    Route::resource('photographers', 'PhotographerController');

    Route::get('photographers/{id}/showPhotographerBySiteId', 'PhotographerController@showPhotographerBySiteId')->name('photographers.showPhotographerBySiteId');

    Route::post('photographers/update', 'PhotographerController@update')->name('photographers.update');


    Route::resource('coordinator', 'CoordinatorController');


    Route::get('coordinator/{id}/showCoordinatorBySiteId', 'CoordinatorController@showCoordinatorBySiteId')->name('coordinator.showCoordinatorBySiteId');

    Route::post('coordinator/update', 'CoordinatorController@update')->name('coordinator.update');



    Route::resource('primaryinvestigator', 'PrimaryInvestigatorController');

    Route::post('primaryinvestigator/update', 'PrimaryInvestigatorController@update')->name('primaryinvestigator.update');


    Route::get('primaryinvestigator/{id}/showSiteId', 'PrimaryInvestigatorController@showSiteId')->name('primaryinvestigator.showSiteId');


    Route::get('modalities/{id}/showChild', 'ModilityController@showChild')->name('modalities.showChild');

    Route::get('modalities/{id}/editChild', 'ModilityController@editChild')->name('modalities.editChild');


    Route::get('modalities/{id}/destroy', 'ModilityController@destroy')->name('modalities.destroy');

    Route::get('childmodilities/{id}/destroy', 'ChildModilitiesController@destroy')->name('childmodilities.destroy');

    Route::get('childmodilities/{id}/restoreChild', 'ChildModilitiesController@restoreChild')->name('childmodilities.restoreChild');

    Route::get('modalities/{id}/replicateParent', 'ModilityController@replicateParent')->name('modalities.replicateParent');

    Route::get('modalities/{id}/restoreParent', 'ModilityController@restoreParent')->name('modalities.restoreParent');

    Route::get('primaryinvestigator/{id}/destroy', 'PrimaryInvestigatorController@destroy')->name('primaryinvestigator.destroy');

    Route::get('coordinator/{id}/destroy', 'CoordinatorController@destroy')->name('coordinator.destroy');

    Route::get('others/{id}/destroy', 'OtherController@destroy')->name('others.destroy');

    Route::get('photographers/{id}/destroy', 'PhotographerController@destroy')->name('photographers.destroy');

    Route::resource('studySite', 'StudySiteController');



    Route::post('studySite/update', 'StudySiteController@update')->name('studySite.update');

    Route::post('studySite/updateStudySite', 'StudySiteController@updateStudySite')->name('studySite.updateStudySite');

    Route::post('studySite/updatePrimaryInvestigator', 'StudySiteController@updatePrimaryInvestigator')->name('studySite.updatePrimaryInvestigator');

    Route::post('studySite/insertCoordinators', 'StudySiteController@insertCoordinators')->name('studySite.insertCoordinators');

    Route::post('studySite/deleteSiteCoordinator', 'StudySiteController@deleteSiteCoordinator')->name('studySite.deleteSiteCoordinator');

    // CHM-Amir
    Route::get('trail_logs', 'TrailLogController@index')->name('trail_logs.list');
});

// for checking subject ID
Route::get('check-subject', 'SubjectController@checkSubject')->name('subjects.check-subject');

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::get('preference/list', 'PreferenceController@index')->name('preference.list');
    Route::post('preference/updatePreference', 'PreferenceController@updatePreference')->name('preference.updatePreference');
});
