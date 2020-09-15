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
    Route::resource('dashboard','DashboardController');
    Route::resource('grading','GradingController');
    Route::resource('qualitycontrol','QualityControlController');
    Route::resource('studytools','StudyToolsController');
    Route::resource('systemtools','SystemToolsController');
    Route::resource('studydesign','StudyDesignController');

});
Route::group(['middleware' => ['auth','web']],function(){
    Route::get('change-role/{role_id}','DashboardController@switch_role')->name('switch_role');

});
