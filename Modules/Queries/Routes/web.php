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
    Route::resource('notifications','AppNotificationsController');
    Route::get('notifications', 'AppNotificationsController@index')->name('notifications.index');
    Route::post('notifications/markAllNotificationToRead','AppNotificationsController@markAllNotificationToRead')->name('notifications.markAllNotificationToRead');
    Route::post('notifications/markAsUnRead','AppNotificationsController@markAsUnRead')->name('notifications.markAsUnRead');
    Route::post('notifications/markAsRead','AppNotificationsController@markAsRead')->name('notifications.markAsRead');
    Route::post('notifications/removeNotification','AppNotificationsController@removeNotification')->name('notifications.removeNotification');
    Route::post('notifications/update', 'AppNotificationsController@update')->name('notifications.update');
    Route::get('queries/chatindex','QueriesController@chatindex')->name('queries.chatindex');



    Route::post('queries/loadHtml', 'QueriesController@loadHtml')->name('queries.loadHtml');
    //Route::post('queries/update', 'QueriesController@update')->name('queries.update');
    Route::post('queries/usersDropDownListQuestion', 'QueriesController@usersDropDownListQuestion')->name('queries.usersDropDownListQuestion');
    Route::post('queries/loadAllQueriesByStudyId', 'QueriesController@loadAllQueriesByStudyId')->name('queries.loadAllQueriesByStudyId');
    Route::post('queries/loadAllQuestionById', 'QueriesController@loadAllQuestionById')->name('queries.loadAllQuestionById');
    Route::post('queries/loadAllCloseQuestionById', 'QueriesController@loadAllCloseQuestionById')->name('queries.loadAllCloseQuestionById');
    Route::post('queries/loadAllCloseFormPhaseById', 'QueriesController@loadAllCloseFormPhaseById')->name('queries.loadAllCloseFormPhaseById');
    Route::post('queries/loadFormByPhaseId', 'QueriesController@loadFormByPhaseId')->name('queries.loadFormByPhaseId');
    Route::post('queries/showCommentsById', 'QueriesController@showCommentsById')->name('queries.showCommentsById');
    Route::post('queries/showQuestionsById', 'QueriesController@showQuestionsById')->name('queries.showQuestionsById');
    Route::post('queries/showFormByQueryId', 'QueriesController@showFormByQueryId')->name('queries.showFormByQueryId');
    Route::post('queries/queryReply', 'QueriesController@queryReply')->name('queries.queryReply');
    Route::post('queries/queryQuestionReply', 'QueriesController@queryQuestionReply')->name('queries.queryQuestionReply');
    Route::post('queries/getStudyDataByStudyId', 'QueriesController@getStudyDataByStudyId')->name('queries.getStudyDataByStudyId');
    Route::post('queries/storeFormQueries', 'QueriesController@storeFormQueries')->name('queries.storeFormQueries');
    Route::post('queries/replyFormQueries', 'QueriesController@replyFormQueries')->name('queries.replyFormQueries');
    Route::post('queries/storeQuestionQueries', 'QueriesController@storeQuestionQueries')->name('queries.storeQuestionQueries');
    Route::post('queries/usersDropDownListForm', 'QueriesController@usersDropDownListForm')->name('queries.usersDropDownListForm');


});
