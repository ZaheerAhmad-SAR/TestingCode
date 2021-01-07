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


Route::group(['middleware' => ['auth','web']],function(){

    Route::resource('bug-reporting','BugReportingController');
    Route::get('bug-reporting/{id}/destroy', 'BugReportingController@destroy')->name('bug-reporting.destroy');
    Route::post('bug-reporting/update', 'BugReportingController@update')->name('bugReporting.update');
});

//Route::prefix('bugreporting')->group(function() {
//    Route::get('/', 'BugReportingController@index');
//});
