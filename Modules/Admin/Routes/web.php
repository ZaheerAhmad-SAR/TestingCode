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


Route::prefix('admin')->group(function() {
    Route::get('/', 'AdminController@index');
});

Route::group(['middleware' => ['auth','web']],function(){

    Route::resource('unittest','AllUnitTestController');
});
Route::group(['middleware' => ['auth','web','roles'],'roles'=>['admin']],function(){
    Route::resource('sites','SiteController');
    Route::resource('studies','StudyController');
    Route::resource('devices','DeviceController');
    Route::resource('modalities','ModilityController');
    Route::resource('diseaseCohort','DiseaseCohortController');
    Route::get('device/{id}','DeviceController@getModal');


    Route::post('modalities/update','ModilityController@update')->name('updateModalities');

    Route::resource('subjects','SubjectController');

    Route::resource('others','OtherController');

    Route::post('others/update','OtherController@update')->name('updateOthers');

    //routes for options groups

    Route::resource('optionsGroup','OptionsGroupController');

    Route::post('optionsGroup/update','OptionsGroupController@update')->name('updateOptionsGroup');

    // routes for form managment
    Route::resource('forms','FormController');
    Route::post('forms/add_questions','FormController@add_questions')->name('addQuestions');
    Route::get('forms/step_by_phaseId/{id}','FormController@get_steps_by_phaseId')->name('stepbyphaseId');
    Route::get('forms/sections_by_stepId/{id}','FormController@get_section_by_stepId')->name('sectionsbystepId');
    Route::post('studyStatus','StudyController@studyStatus')->name('study.studyStatus');
    Route::post('changeStatus/{id}','StudyController@changeStatus')->name('studies.changeStatus');
    Route::get('forms/get_allQuestions/{id}','FormController@get_allQuestions')->name('get_allQuestions');
    //end
     // routes for study managment
    Route::resource('study','StudyStructureController');
    Route::get('study_phases','StudyStructureController@getallphases')->name('getPhases');
    Route::post('study/update','StudyStructureController@update')->name('updatePhase');
    Route::DELETE('steps/delete_steps/{step_id}','StudyStructureController@destroySteps')->name('deleteSteps');
    Route::post('steps/store_steps','StudyStructureController@store_steps')->name('steps.save');
    Route::post('steps/updateSteps','StudyStructureController@update_steps')->name('steps.update');
    Route::post('studies/studyStatus','StudyController@studyStatus')->name('studies.studyStatus');
    //end
    // routes for adding sections
    // Route::resource('section','SectionController');
    Route::resource('sections','SectionController');
    Route::post('section','SectionController@getSectionby_id')->name('getSections');
    Route::post('section/update','SectionController@update')->name('updateSections');
    //end

    Route::resource('childmodilities','ChildModilitiesController');

    Route::post('childmodilities/update','ChildModilitiesController@update')->name('updateChildmodilities');

    Route::get('modalities/{id}/childshow','ModilityController@child')->name('modalities.childshow');

    Route::resource('photographers','PhotographerController');

    Route::post('photographers/update','PhotographerController@update')->name('updatePhotographers');


    Route::resource('coordinator','CoordinatorController');


    Route::post('coordinator/update','CoordinatorController@update')->name('updateCoordinator');



    Route::resource('primaryinvestigator','PrimaryInvestigatorController');

    Route::post('primaryinvestigator/update','PrimaryInvestigatorController@update')->name('updatePrimaryinvestigator');


    Route::get('primaryinvestigator/{id}/showPrimaryInvestigatorById','PrimaryInvestigatorController@showPrimaryInvestigatorById')->name('primaryinvestigator.showPrimaryInvestigatorById');


    Route::get('modalities/{id}/showChild','ModilityController@showChild')->name('modalities.showChild');



    Route::get('modalities/{id}/editChild','ModilityController@editChild')->name('modalities.editChild');


    Route::get('modalities/{id}/destroy','ModilityController@destroy')->name('modalities.destroy');

    Route::get('childmodilities/{id}/destroy','ChildModilitiesController@destroy')->name('childmodilities.destroy');

    Route::get('childmodilities/{id}/restoreChild','ChildModilitiesController@restoreChild')->name('childmodilities.restoreChild');

    Route::get('modalities/{id}/replicateParent','ModilityController@replicateParent')->name('modalities.replicateParent');

    Route::get('modalities/{id}/restoreParent','ModilityController@restoreParent')->name('modalities.restoreParent');

    Route::get('primaryinvestigator/{id}/destroy','PrimaryInvestigatorController@destroy')->name('primaryinvestigator.destroy');

    Route::get('coordinator/{id}/destroy','CoordinatorController@destroy')->name('coordinator.destroy');

    Route::get('others/{id}/destroy','OtherController@destroy')->name('others.destroy');

    Route::get('photographers/{id}/destroy','PhotographerController@destroy')->name('photographers.destroy');

});

