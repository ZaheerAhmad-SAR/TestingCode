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

/*Route::prefix('queries')->group(function() {
    Route::get('/', 'QueriesController@index');
});*/

Route::group(['middleware' => ['auth','web']],function(){
    Route::resource('queries','QueriesController');
    Route::get('queries/chatindex','QueriesController@chatindex')->name('queries.chatindex');
    Route::post('queries/loadHtml', 'QueriesController@loadHtml')->name('queries.loadHtml');
    Route::post('queries/loadAllQueriesByStudyId', 'QueriesController@loadAllQueriesByStudyId')->name('queries.loadAllQueriesByStudyId');
    Route::post('queries/showCommentsById', 'QueriesController@showCommentsById')->name('queries.showCommentsById');
    Route::post('queries/queryReply', 'QueriesController@queryReply')->name('queries.queryReply');
    Route::post('queries/getStudyDataByStudyId', 'QueriesController@getStudyDataByStudyId')->name('queries.getStudyDataByStudyId');

});
