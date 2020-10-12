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

Route::prefix('userroles')->group(function() {
    Route::get('/', 'UserRolesController@index');
	Route::get('getallUsers','UserRolesController@getallUsers')->name('getallUsers');
});


Route::group(['middleware' => ['auth','web','roles']],function(){

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
    Route::get('update_profile', 'UserController@update_profile')->name('users.updateProfile');
    Route::post('users/assignusers', 'UserController@assign_users')->name('users.assignUsers');
    Route::post('users/resetpassword', 'UserController@resetpassword')->name('users.resetpassword');


});
Route::group(['middleware' => ['auth','web']],function(){
    Route::get('update_user/{id}', 'UserController@update_user')->name('users.updateUser');
    Route::get('change-role/{role_id}','DashboardController@switch_role')->name('switch_role');

});
