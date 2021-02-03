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

    Route::resource('bug-reports','BugReportingController');
    Route::get('bug-reports/{id}/destroy', 'BugReportingController@destroy')->name('bug-reports.destroy');
    Route::post('bug-reports/getCurrentRowData', 'BugReportingController@getCurrentRowData')->name('bug-reports.getCurrentRowData');
    Route::post('bug-reports/update', 'BugReportingController@update')->name('bug-reports.update');
});

//Route::prefix('bugreporting')->group(function() {
//    Route::get('/', 'BugReportingController@index');
//});
