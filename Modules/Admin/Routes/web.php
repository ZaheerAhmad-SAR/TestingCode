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

// php info
// Route::get('phpInfo', function(){
//     phpinfo();
// });

//test transmission view
// Route::get('transmissions/transmissionData', function () {
//     return view('admin::test_transmission_api');
// });
// transmission end point

// Route::get('error-message', function(){

//     return view('errors.404');
// });
// route to check if subject exists
Route::post('subjects/check_variable', 'SubjectController@check_variable_name')->name('subjects.checkVariable');
// end
Route::post('transmissions/transmissionData', 'TransmissionController@transmissionData')->name('transmissions.transmissionData');
Route::post('studies/getAssignedAdminsToStudy', 'StudyController@getAssignedAdminsToStudy')->name('studies.getAssignedAdminsToStudy');
Route::post('transmissions/getAllPIBySiteId', 'TransmissionController@getAllPIBySiteId')->name('transmissions.getAllPIBySiteId');
Route::post('transmissions/queryTransmissionMail', 'TransmissionController@queryTransmissionMail')->name('transmissions.queryTransmissionMail');
Route::post('transmissions/queryTransmissionMailResponse', 'TransmissionController@queryTransmissionMailResponse')->name('transmissions.queryTransmissionMailResponse');
Route::post('transmissions/queryResponseSave', 'TransmissionController@queryResponseSave')->name('transmissions.queryResponseSave');
Route::post('transmissions/showResponseById', 'TransmissionController@showResponseById')->name('transmissions.showResponseById');
Route::post('transmissions/getQueryByTransmissionId', 'TransmissionController@getQueryByTransmissionId')->name('transmissions.getQueryByTransmissionId');
Route::post('transmissions/getSiteByTransmissionId', 'TransmissionController@getSiteByTransmissionId')->name('transmissions.getSiteByTransmissionId');
Route::get('transmissions/verifiedToken/{id}/', 'TransmissionController@verifiedToken')->name('transmissions.verifiedToken');
Route::post('transmissions-status', 'TransmissionController@transmissionStatus')->name('transmissions-status');
Route::prefix('admin')->group(function () {
    Route::get('/', 'AdminController@index');
});
Route::get('modalities/{id}/showChild', 'ModilityController@showChild')->name('modalities.showChild');
Route::group(['middleware' => ['auth', 'web']], function () {

    Route::get('get_steps', 'StudyStructureController@get_steps')->name('study.getSteps');
    Route::get('study_phases', 'StudyStructureController@getallphases')->name('getPhases');
    Route::get('forms/get_phases/{id}', 'FormController@get_phases')->name('forms.get_phases');
    Route::post('forms/create_filter_session', 'FormController@create_filter_session')->name('forms.makeFilterSession');
    Route::post('study/update', 'StudyStructureController@update')->name('study.updatePhase');
    // for clone steps
    //Route::resource('cloneSteps', 'CloneStepsController');
    Route::post('clone_steps', 'CloneStepsController@clone_steps')->name('cloneSteps.cloneSteps');
    Route::post('clone_phase', 'CloneStepsController@clone_phase')->name('cloneSteps.clonePhase');
    Route::post('clone_section', 'CloneStepsController@clone_section')->name('cloneSteps.cloneSection');
    // for steps
    Route::DELETE('steps/delete_steps/{step_id}', 'StudyStructureController@destroySteps')->name('steps.deleteSteps');
    Route::post('steps/store_steps', 'StudyStructureController@store_steps')->name('steps.save');
    Route::post('steps/updateSteps', 'StudyStructureController@update_steps')->name('steps.update');

    Route::post('steps/activate_step/{step_id}', 'StudyStructureController@activateStep')->name('steps.activateStep');
    Route::post('steps/deActivate_step/{step_id}', 'StudyStructureController@deActivateStep')->name('steps.deActivateStep');
    // For Reports
    Route::resource('reports', 'ReportController');
    // for Section
    Route::resource('sections', 'SectionController');
    Route::post('section', 'SectionController@getSectionby_id')->name('section.getSections');
    Route::post('section/update', 'SectionController@update')->name('section.updateSections');
    /// for form management
    Route::get('forms/step_by_phaseId/{id}', 'FormController@get_steps_by_phaseId')->name('forms.stepbyphaseId');
    Route::get('forms/get_questions_for_calculation/{id}', 'FormController@get_questions_calculation')->name('forms.calculationQuestions');
    Route::resource('forms', 'FormController');
    Route::post('forms/add_questions', 'FormController@add_questions')->name('forms.addQuestions');
    Route::post('forms/updateQuestion', 'FormController@update_questions')->name('forms.updateQuestion');
    Route::get('forms/sections_against_step/{id}', 'FormController@get_sections_against_step')->name('forms.sections_against_step');
    Route::post('forms/check_variable', 'FormController@check_variable_name')->name('forms.checkVariable');
    Route::post('forms/isStepActive/{step_id}', 'FormController@isStepActive')->name('steps.isStepActive');
    Route::post('forms/isThisStepHasData', 'FormController@isThisStepHasData')->name('steps.isThisStepHasData');
    Route::post('forms/getStepVersion/{step_id}', 'FormController@getStepVersion')->name('forms.getStepVersion');
    Route::get('forms/show_available_variable_names/{step_id}', 'FormController@show_available_variable_names')->name('forms.show_available_variable_names');
    // skip logic
    Route::resource('skiplogic', 'SkipLogicController');
    Route::get('skiplogic/sections_for_skip_logic/{id}', 'SkipLogicController@sections_skip_logic')->name('skiplogic.sectionsSkip');
    Route::get('skiplogic/sections_for_skip_logic_deactivate/{id}', 'SkipLogicController@sections_skip_logic_deactivate')->name('skiplogic.sectionsSkipdeactivate');
    Route::get('skiplogic/questions_for_skip_logic/{id}', 'SkipLogicController@questions_skip_logic')->name('skiplogic.questionsSkip');
    Route::get('skiplogic/questions_for_skip_logic_deactivate/{id}', 'SkipLogicController@questions_skip_logic_deactivate')->name('skiplogic.questionsSkipdeactivate');
    Route::get('skiplogic/options_for_skip_logic_deactivate/{id}', 'SkipLogicController@options_skip_logic_deactivate')->name('skiplogic.optionsSkipdeactivate');
    Route::get('skiplogic/options_for_skip_logic_activate/{id}', 'SkipLogicController@options_skip_logic_activate')->name('skiplogic.optionsSkipactivate');
    Route::post('skiplogic/steps_to_skip', 'SkipLogicController@getSteps_toskip')->name('skiplogic.get_steps_skip_logic');

    Route::get('skiplogic/skip_logic/{id}', 'SkipLogicController@skip_question_on_click')->name('skiplogic.skipLogic');
    Route::get('skiplogic/text_skip_logic/{id}', 'SkipLogicController@skip_question_on_text')->name('skiplogic.textskipLogic');
    Route::post('skiplogic/add_skip_logic', 'SkipLogicController@add_skipLogic')->name('skiplogic.apply_skip_logic');
    // skip logic on cohort
    Route::get('skiplogic/skip_logic_cohort/{id}/{formTypeId?}/{modalityId?}', 'SkipLogicController@skip_logic_cohort')->name('skiplogic.skiponcohort');
    Route::post('skiplogic/skip_via_cohort', 'SkipLogicController@git_steps_for_checks_deactivate_cohort')->name('skiplogic.get_steps_skip_logic_deactivate_via_cohort');
    Route::post('skiplogic/add_skip_logic_cohort_based', 'SkipLogicController@add_skipLogic_cohort_based')->name('skiplogic.apply_skip_logic_cohort_based');
    // skip logic
    // routes for skip logic on Questions with type Number
    // Start
    Route::get('skipNumber/num_skip_logic/{id}', 'SkipNumberController@skip_question_on_number')->name('skipNumber.numskipLogic');
    Route::post('skipNumber/add_skip_logic_num', 'SkipNumberController@add_skipLogic_num')->name('skipNumber.apply_skip_logic_num');
    Route::get('skipNumber/sections_for_skip_logic/{id}', 'SkipNumberController@sections_skip_logic')->name('skipNumber.sectionsSkip');
    Route::get('skipNumber/sections_for_skip_logic_deactivate/{id}', 'SkipNumberController@sections_skip_logic_deactivate')->name('skipNumber.sectionsSkipdeactivate');
    Route::get('skipNumber/questions_for_skip_logic/{id}', 'SkipNumberController@questions_skip_logic')->name('skipNumber.questionsSkip');
    Route::get('skipNumber/questions_for_skip_logic_deactivate/{id}', 'SkipNumberController@questions_skip_logic_deactivate')->name('skipNumber.questionsSkipdeactivate');
    Route::get('skipNumber/options_for_skip_logic_deactivate/{id}', 'SkipNumberController@options_skip_logic_deactivate')->name('skipNumber.optionsSkipdeactivate');
    Route::get('skipNumber/options_for_skip_logic_activate/{id}', 'SkipNumberController@options_skip_logic_activate')->name('skipNumber.optionsSkipactivate');
    Route::get('skipNumber/update_skip_checks_num/{id}', 'SkipNumberController@update_skip_checks')->name('skipNumber.updateSkipNum');
    Route::post('skipNumber/update_skip_checks_number', 'SkipNumberController@update_skip_checks_on_number')->name('skipNumber.updateSkipNumberChecks');
    Route::get('skipNumber/update_skip_checks_text/{id}', 'SkipNumberController@update_skip_checks_text')->name('skipNumber.updateSkipText');
    Route::post('skipNumber/update_skip_checks_textbox', 'SkipNumberController@update_skip_checks_on_textbox')->name('skipNumber.updateSkipTextboxChecks');
    // End
    // for type text
    Route::post('skipNumber/add_skip_logic_text', 'SkipNumberController@add_skipLogic_text')->name('skipNumber.apply_skip_logic_text');
    // routes for skip logic on Questions with type Number
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
    Route::post('annotation/add_annotation', 'AnnotationController@store_new_annotation')->name('annotation.addAnnotation');
    Route::post('sites/checkIfSiteIsExist', 'SiteController@checkIfSiteIsExist')->name('sites.checkIfSiteIsExist');
    Route::get('studySite/assignedSites', 'StudySiteController@assignedSites')->name('studySite.assignedSites');
    Route::post('studySite/removeAssignedSites', 'StudySiteController@removeAssignedSites')->name('studySite.removeAssignedSites');
});
Route::group(['middleware' => ['auth', 'web', 'roles']], function () {
    
    Route::resource('studies', 'StudyController');
    Route::resource('sites', 'SiteController');
    Route::post('sites/update', 'SiteController@update')->name('sites.updateSites');

    Route::DELETE('sites/destroy/{sites_id}', 'SiteController@destroy')->name('sites.destroy');


    Route::post('studies/update_studies', 'StudyController@update_studies')->name('studies.update_studies');
    Route::resource('devices', 'DeviceController');
    Route::resource('modalities', 'ModilityController');
    Route::resource('diseaseCohort', 'DiseaseCohortController');
    Route::get('device/{id}', 'DeviceController@getModal');


    Route::post('modalities/update', 'ModilityController@update')->name('modalities.update');
    // routes for subject
    Route::resource('subjects', 'SubjectController');

    //Route::resource('studyrole','StudyRoleController');

    // routes for form managment

    //end
    // routes for study managment
    Route::resource('study', 'StudyStructureController');


    Route::post('studies/studyStatus', 'StudyController@studyStatus')->name('studies.studyStatus');
    Route::post('studies/cloneStudy', 'StudyController@cloneStudy')->name('studies.cloneStudy');
    Route::post('studies/exportStudy', 'StudyController@exportStudy')->name('studies.exportStudy');

    //end
    // routes for adding sections
    // Route::resource('section','SectionController');

    //end
    // Modalities routes
    Route::resource('childmodilities', 'ChildModilitiesController');

    Route::post('childmodilities/update', 'ChildModilitiesController@update')->name('childmodilities.update');

    Route::get('modalities/{id}/childshow', 'ModilityController@child')->name('modalities.childshow');


    Route::get('modalities/{id}/editChild', 'ModilityController@editChild')->name('modalities.editChild');


    Route::get('modalities/{id}/destroy', 'ModilityController@destroy')->name('modalities.destroy');

    Route::get('childmodilities/{id}/destroy', 'ChildModilitiesController@destroy')->name('childmodilities.destroy');

    Route::get('childmodilities/{id}/restoreChild', 'ChildModilitiesController@restoreChild')->name('childmodilities.restoreChild');

    Route::get('modalities/{id}/replicateParent', 'ModilityController@replicateParent')->name('modalities.replicateParent');

    Route::get('modalities/{id}/restoreParent', 'ModilityController@restoreParent')->name('modalities.restoreParent');

    Route::resource('studySite', 'StudySiteController');

    Route::post('studySite/update', 'StudySiteController@update')->name('studySite.update');


    Route::post('studySite/updateStudySite', 'StudySiteController@updateStudySite')->name('studySite.updateStudySite');

    Route::post('studySite/updatePrimaryInvestigator', 'StudySiteController@updatePrimaryInvestigator')->name('studySite.updatePrimaryInvestigator');

    Route::post('studySite/insertCoordinators', 'StudySiteController@insertCoordinators')->name('studySite.insertCoordinators');

    Route::post('studySite/deleteSiteCoordinator', 'StudySiteController@deleteSiteCoordinator')->name('studySite.deleteSiteCoordinator');

    // CHM-Amir
    Route::get('trail_logs', 'TrailLogController@index')->name('trail_logs.list');

    Route::get('users_activities', 'TrailLogController@usersActivities')->name('trail_logs.usersActivities');
    //Transmissions Routes
    Route::resource('transmissions', 'TransmissionController');

    Route::get('transmissions-study-edit/{id}', 'TransmissionController@transmissionsStudyEdit')->name('transmissions-study-edit');

    Route::get('study-transmissions', 'TransmissionController@studyTransmissions')->name('transmissions.study-transmissions');
});



// for checking subject ID
Route::get('check-subject', 'SubjectController@checkSubject')->name('subjects.check-subject');

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::get('preference/list', 'PreferenceController@index')->name('preference.list');
    Route::post('preference/updatePreference', 'PreferenceController@updatePreference')->name('preference.updatePreference');
    //Add Preference
    Route::post('preference/loadAddPreferenceForm', 'PreferenceController@loadAddPreferenceForm')->name('preference.loadAddPreferenceForm');
    Route::post('preference/submitAddPreferenceForm', 'PreferenceController@submitAddPreferenceForm')->name('preference.submitAddPreferenceForm');

    //primaryinvestigator
    Route::resource('primaryinvestigator', 'PrimaryInvestigatorController');
    Route::post('primaryinvestigator/update', 'PrimaryInvestigatorController@update')->name('primaryinvestigator.update');
    Route::get('primaryinvestigator/{id}/showSiteId', 'PrimaryInvestigatorController@showSiteId')->name('primaryinvestigator.showSiteId');
    Route::get('primaryinvestigator/{id}/destroy', 'PrimaryInvestigatorController@destroy')->name('primaryinvestigator.destroy');

    //coordinator
    Route::resource('coordinator', 'CoordinatorController');
    Route::get('coordinator/{id}/showCoordinatorBySiteId', 'CoordinatorController@showCoordinatorBySiteId')->name('coordinator.showCoordinatorBySiteId');
    Route::post('coordinator/update', 'CoordinatorController@update')->name('coordinator.update');
    Route::get('coordinator/{id}/destroy', 'CoordinatorController@destroy')->name('coordinator.destroy');

    //photographers
    Route::resource('photographers', 'PhotographerController');
    Route::get('photographers/{id}/showPhotographerBySiteId', 'PhotographerController@showPhotographerBySiteId')->name('photographers.showPhotographerBySiteId');
    Route::post('photographers/update', 'PhotographerController@update')->name('photographers.update');
    Route::get('photographers/{id}/destroy', 'PhotographerController@destroy')->name('photographers.destroy');

    //others
    Route::resource('others', 'OtherController');
    Route::get('others/{id}/showOtherBySiteId', 'OtherController@showOtherBySiteId')->name('others.showOtherBySiteId');
    Route::post('others/update', 'OtherController@update')->name('others.update');
    Route::get('others/{id}/destroy', 'OtherController@destroy')->name('others.destroy');

    Route::get('study/permanentlyDeleteStudyAndItsRecord/{id}', 'StudyController@permanentlyDeleteStudyAndItsRecord')->name('study.permanentlyDeleteStudyAndItsRecord');
});

Route::post('tinymce-image_upload', 'TinyMceController@uploadImage')->name('tinymce.image_upload');
