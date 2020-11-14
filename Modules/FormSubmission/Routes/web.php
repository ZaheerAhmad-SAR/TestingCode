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
    // Jawad
    Route::get('forms/show/{phase_id}/{step_id}', 'PreviewFormController@show')->name('forms.show');
    //SubjectFormLoader
    Route::get('subjectFormLoader/{study_id}/{subject_id}/{showAllQuestions?}', 'SubjectFormLoaderController@showSubjectForm')->name('subjectFormLoader.showSubjectForm');
    //SubjectFormSubmission
    Route::post('SubjectFormSubmission/submitStudyPhaseStepQuestion', 'SubjectFormSubmissionController@submitQuestion')->name('SubjectFormSubmission.submitStudyPhaseStepQuestion');
    Route::post('SubjectFormSubmission/submitStudyPhaseStepQuestionForm', 'SubjectFormSubmissionController@submitForm')->name('SubjectFormSubmission.submitStudyPhaseStepQuestionForm');
    Route::post('SubjectFormSubmission/openSubjectFormToEdit', 'SubjectFormSubmissionController@openSubjectFormToEdit')->name('SubjectFormSubmission.openSubjectFormToEdit');
    Route::post('SubjectFormSubmission/lockFormData', 'SubjectFormSubmissionController@lockFormData')->name('SubjectFormSubmission.lockFormData');
    Route::post('SubjectFormSubmission/unlockFormData', 'SubjectFormSubmissionController@unlockFormData')->name('SubjectFormSubmission.unlockFormData');

    Route::post('SubjectAdjudicationFormSubmission/submitAdjudicationFormStudyPhaseStepQuestion', 'SubjectAdjudicationFormSubmissionController@submitAdjudicationFormQuestion')->name('SubjectAdjudicationFormSubmission.submitAdjudicationFormStudyPhaseStepQuestion');
    Route::post('SubjectAdjudicationFormSubmission/submitStudyPhaseStepQuestionAdjudicationForm', 'SubjectAdjudicationFormSubmissionController@submitAdjudicationForm')->name('SubjectAdjudicationFormSubmission.submitStudyPhaseStepQuestionAdjudicationForm');
    Route::post('SubjectAdjudicationFormSubmission/openSubjectAdjudicationFormToEdit', 'SubjectAdjudicationFormSubmissionController@openSubjectAdjudicationFormToEdit')->name('SubjectAdjudicationFormSubmission.openSubjectAdjudicationFormToEdit');
    //Assign Roles ToPhase and Step
    Route::post('assignRolesPhaseStep/getAssignRolesToPhaseForm', 'AssignRolesPhaseStepController@getAssignRolesToPhaseForm')->name('assignRolesPhaseStep.getAssignRolesToPhaseForm');
    Route::post('assignRolesPhaseStep/getAssignRolesToPhaseStepForm', 'AssignRolesPhaseStepController@getAssignRolesToPhaseStepForm')->name('assignRolesPhaseStep.getAssignRolesToPhaseStepForm');
    Route::post('assignRolesPhaseStep/submitAssignRolesToPhaseForm', 'AssignRolesPhaseStepController@submitAssignRolesToPhaseForm')->name('assignRolesPhaseStep.submitAssignRolesToPhaseForm');
    Route::post('assignRolesPhaseStep/submitAssignRolesToPhaseStepForm', 'AssignRolesPhaseStepController@submitAssignRolesToPhaseStepForm')->name('assignRolesPhaseStep.submitAssignRolesToPhaseStepForm');

    //Validation Rules
    Route::post('validationRule/filterRulesDataValidation/', 'ValidationRuleController@filterRulesDataValidation')->name('validationRule.filterRulesDataValidation');
    Route::post('validationRule/getQuestionValidationRules/', 'ValidationRuleController@getQuestionValidationRules')->name('validationRule.getQuestionValidationRules');
    Route::post('validationRule/getNumParams/', 'ValidationRuleController@getNumParams')->name('validationRule.getNumParams');
    // Form Validation
    Route::post('subjectFormSubmission/validateSingleQuestion', 'SubjectFormSubmissionController@validateSingleQuestion')->name('subjectFormSubmission.validateSingleQuestion');
    Route::post('subjectFormSubmission/validateSectionQuestionsForm', 'SubjectFormSubmissionController@validateSectionQuestionsForm')->name('subjectFormSubmission.validateSectionQuestionsForm');

    Route::post('subjectAdjudicationFormSubmission/validateSingleQuestion', 'SubjectAdjudicationFormSubmissionController@validateSingleQuestion')->name('subjectAdjudicationFormSubmission.validateSingleQuestion');
    Route::post('subjectAdjudicationFormSubmission/validateSectionQuestionsForm', 'SubjectAdjudicationFormSubmissionController@validateSectionQuestionsForm')->name('subjectAdjudicationFormSubmission.validateSectionQuestionsForm');
    //Assign Phase To Subject
    Route::post('assignPhaseToSubject/loadAssignPhaseToSubjectForm', 'AssignPhaseToSubjectController@loadAssignPhaseToSubjectForm')->name('assignPhaseToSubject.loadAssignPhaseToSubjectForm');
    Route::post('assignPhaseToSubject/submitAssignPhaseToSubjectForm', 'AssignPhaseToSubjectController@submitAssignPhaseToSubjectForm')->name('assignPhaseToSubject.submitAssignPhaseToSubjectForm');
});
