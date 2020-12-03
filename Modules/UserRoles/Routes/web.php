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
    Route::post('/users/invite', 'UserController@process_invites')->name('process_invite');
    Route::get('/registration/{token}', 'UserController@registration_view')->name('registration');


Route::prefix('userroles')->group(function() {
    Route::get('/', 'UserRolesController@index');
	Route::get('getallUsers','UserRolesController@getallUsers')->name('getallUsers');
});

Route::group(['middleware' => ['auth','web']],function (){
    Route::get('update_profile', 'UserController@update_profile')->name('users.updateProfile');
});

Route::group(['middleware' => ['auth','web','roles']],function(){

/** -----------------------Amir Khan route ---------------------------------- **/

// form 1 grading
    Route::get('excel-grading','GradingController@excelGrading')->name('excel-grading');
// form 2 grading
    Route::get('excel-grading2','GradingController@excelGrading2')->name('excel-grading2');
// form 1 qc
    Route::get('excel-qc','QualityControlController@excelQC')->name('excel-qc');
// form 2 qc
    Route::get('excel-qc2','QualityControlController@excelQC2')->name('excel-qc2');
// form 1 adjudication
    Route::get('excel-adjudication','AdjudicationController@excelAdjudication')->name('excel-adjudication');
// form 2 adjudication
    Route::get('excel-adjudication2','AdjudicationController@excelAdjudication2')->name('excel-adjudication2');

// form 1 grading status
    Route::get('excel-grading-status','GradingController@excelGradingStatus')->name('excel.grading-status');
// form 2 grading status
    Route::get('excel-grading2-status','GradingController@excelGradingStatus2')->name('excel.grading-status2');

// grading status
    Route::get('grading-status','GradingController@gradingStatus')->name('grading.status');

// assign work routes*
    Route::get('assign-work','GradingController@assignWork')->name('assign-work');
    Route::post('save-assign-work','GradingController@saveAssignWork')->name('save-assign-work');
    
    //Route::post('update-assign-work','GradingController@updateAssignWork')->name('update-assign-work');

// get users for form type, assign work section*
    Route::get('get-form-type-users', 'GradingController@getFormTypeUsers')->name('get-form-type-users');
// edit assign work
    //Route::get('edit-assign-work', 'GradingController@editAssignWork')->name('edit-assign-work');
// check assign work*
    Route::post('check-assign-work','GradingController@checkAssignWork')->name('check-assign-work');

// QC Work List*
    Route::get('qc-work-list','QualityControlController@qcWorkList')->name('qualitycontrol.qc-work-list');

// Grading Work List*
    Route::get('grading-work-list','GradingController@gradingWorkList')->name('gradingcontrol.grading-work-list');

// Adjudication Work List*
    Route::get('adjudication-work-list','AdjudicationController@adjudicationWorkList')->name('adjudicationcontroller.adjudication-work-list');

/** ------------------------------ Amir Khan Routes ends ------------------------------------------**/

//Invitation_Routes
    Route::get('/users/invite', 'UserController@invite_view')->name('invite_view');

    Route::resource('roles','RoleController');
    // Permissions
    /*Route::resource('permissions', 'PermissionsController');*/
    Route::resource('users','UserController');
    Route::resource('studyusers','StudyusersController');
    Route::resource('systemusers','SystemusersController');
    Route::resource('dashboard','DashboardController');
    Route::resource('grading','GradingController');
    Route::resource('qualitycontrol','QualityControlController');
    Route::resource('studytools','StudyToolsController');
    Route::resource('systemtools','SystemToolsController');
    Route::resource('studydesign','StudyDesignController');
    // Route::resource('activitylog','ActivityLogController');
    Route::resource('certification','CertificationController');
    Route::resource('data_management','DataManagementController');
    Route::resource('finance','FinanceController');
    Route::resource('adjudication','AdjudicationController');
    Route::resource('eligibility','EligibilityController');
    Route::resource('studyRoles','StudyRolesController');
    Route::post('users/assignusers', 'UserController@assign_users')->name('users.assignUsers');
    Route::post('users/enable_2fa', 'UserController@enable_2fa')->name('users.enable_2fa');


});
Route::group(['middleware' => ['auth','web']],function(){
    Route::get('update_user/{id}', 'UserController@update_user')->name('users.updateUser');
    Route::get('change-role/{role_id}','DashboardController@switch_role')->name('switch_role');

});
