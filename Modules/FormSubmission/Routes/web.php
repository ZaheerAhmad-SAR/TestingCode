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
    Route::get('printForm/{studyId}/{subjectId}/{phaseId}/{stepId}/{formFilledByUserId}', 'PreviewFormController@printForm')->name('printForm');
    //SubjectFormLoader
    Route::get('subjectFormLoader/{study_id}/{subject_id}/{phaseId?}/{stepId?}/{sectionId?}/{isAdjudication?}/{showAllQuestions?}', 'SubjectFormLoaderController@showSubjectForm')->name('subjectFormLoader.showSubjectForm');
    //SubjectFormSubmission
    Route::post('SubjectFormSubmission/submitStudyPhaseStepQuestion', 'SubjectFormSubmissionController@submitQuestion')->name('SubjectFormSubmission.submitStudyPhaseStepQuestion');
    Route::post('SubjectFormSubmission/submitStudyPhaseStepQuestionForm', 'SubjectFormSubmissionController@submitForm')->name('SubjectFormSubmission.submitStudyPhaseStepQuestionForm');
    Route::post('SubjectFormSubmission/openSubjectFormToEdit', 'SubjectFormSubmissionController@openSubjectFormToEdit')->name('SubjectFormSubmission.openSubjectFormToEdit');
    Route::post('SubjectFormSubmission/lockFormData', 'SubjectFormSubmissionController@lockFormData')->name('SubjectFormSubmission.lockFormData');
    Route::post('SubjectFormSubmission/unlockFormData', 'SubjectFormSubmissionController@unlockFormData')->name('SubjectFormSubmission.unlockFormData');
    Route::post('SubjectFormSubmission/deleteFormUploadFile', 'SubjectFormSubmissionController@deleteFormUploadFile')->name('SubjectFormSubmission.deleteFormUploadFile');

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
    Route::post('assignPhaseToSubject/unAssignPhaseToSubject', 'AssignPhaseToSubjectController@unAssignPhaseToSubject')->name('assignPhaseToSubject.unAssignPhaseToSubject');

    // Form Data Export
    Route::get('formDataExport/index', 'FormDataExportController@index')->name('formDataExport.index');
    Route::post('exportType/loadExportTypes', 'ExportTypeController@loadExportTypes')->name('exportType.loadExportTypes');
    Route::post('exportType/loadAddExportTypeForm', 'ExportTypeController@loadAddExportTypeForm')->name('exportType.loadAddExportTypeForm');
    Route::post('exportType/loadEditExportTypeForm', 'ExportTypeController@loadEditExportTypeForm')->name('exportType.loadEditExportTypeForm');
    Route::post('exportType/submitAddExportTypeForm', 'ExportTypeController@submitAddExportTypeForm')->name('exportType.submitAddExportTypeForm');
    Route::put('exportType/submitEditExportTypeForm', 'ExportTypeController@submitEditExportTypeForm')->name('exportType.submitEditExportTypeForm');
    Route::delete('exportType/removeEditExportType', 'ExportTypeController@removeEditExportType')->name('exportType.removeEditExportType');

    Route::post('formDataExport/loadExportFilterForm', 'FormDataExportController@filterForm')->name('formDataExport.loadExportFilterForm');
    Route::get('formDataExport/export', 'FormDataExportController@export')->name('formDataExport.export');

    Route::post('qcQuestionToShow/openShowQuestionsToGraderPopUp', 'QcQuestionToShowController@openShowQuestionsToGraderPopUp')->name('qcQuestionToShow.openShowQuestionsToGraderPopUp');
    Route::post('questionComment/loadQuestionCommentPopup', 'QuestionCommentController@loadQuestionCommentPopup')->name('questionComment.loadQuestionCommentPopup');
    Route::post('questionComment/loadAddQuestionCommentForm', 'QuestionCommentController@loadAddQuestionCommentForm')->name('questionComment.loadAddQuestionCommentForm');
    Route::post('questionComment/submitAddQuestionCommentForm', 'QuestionCommentController@submitAddQuestionCommentForm')->name('questionComment.submitAddQuestionCommentForm');
});
