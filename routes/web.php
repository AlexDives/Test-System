<?php

Route::get('/', 'indexController@index');
Route::post('/auth', 'Ajax\authController@auth');
Route::get('/quit', 'Ajax\authController@quit');

Route::get('/registration', 'Ajax\regTestsController@index');
Route::post('/registration/post', 'Ajax\regTestsController@registration');
Route::post('/Check_login', 'Ajax\regTestsController@check_login');
Route::post('/Check_email', 'Ajax\regTestsController@check_email');
Route::get('/verificate', 'Ajax\regTestsController@verificate');

Route::middleware('authCheck')->group(function () {
    Route::get('/editor', 'editorController@main');

    Route::post('/speedrequest', 'Ajax\requestController@req');
    Route::post('/editor/search', 'editorController@searchTest');

    Route::get('/info', 'editorController@info');
    Route::post('/info/save', 'editorController@saveTestInfo');
    Route::get('/info/new', 'editorController@newTest');
    Route::get('/info/delete', 'editorController@deleteTest');
    Route::get('/info/rollback', 'editorController@rollbackTest');
    Route::post('/info/editors', 'editorController@testEditors');
    Route::post('/info/saveTestEditors', 'editorController@saveTestEditors');

    Route::get('/questions', 'editorController@questions');
    Route::post('/questions/save', 'editorController@saveQuestions');
    Route::post('/questions/speedFillQuest', 'editorController@speedFillQuest');
    Route::post('/questions/loadList', 'editorController@loadQuestList');
    Route::post('/questions/selectedQuest', 'editorController@selectedQuest');
    Route::post('/questions/deleteQuest', 'editorController@deleteQuest');

    Route::get('/manager', 'managerController@main');
    Route::get('/manager/loadUserTable', 'managerController@loadUserTable');
    Route::post('/manager/save', 'managerController@saveUser');
    Route::post('/manager/delete', 'managerController@deleteUser');

    Route::get('/statistic', 'statisticController@main');
    Route::post('/statistic/refresh', 'statisticController@loadStats');

    Route::get('/pers/list', 'persController@persList');
    Route::post('/pers/check', 'persController@persCheck');
    Route::get('/pers/cabinet', 'persController@persCabinet');

    Route::get('/persons', 'personsCabinetController@index');
    Route::get('/persons/events', 'personsCabinetController@events');
    Route::post('/persons/regevent', 'personsCabinetController@regevent');
    Route::post('/persons/savevent', 'personsCabinetController@savevent');
    Route::post('/persons/createPdf', 'personsCabinetController@createPdf');
    Route::post('/persons/sendRequest', 'personsCabinetController@sendRequest');

    Route::get('/test/start', 'testController@start');
    Route::post('/test/loadQuestList', 'testController@loadQuestList');
    Route::post('/test/selectedQuest', 'testController@selectedQuest');
    Route::post('/test/confirmResponse', 'testController@confirmResponse');
    Route::post('/test/timeLeft', 'testController@timeLeft');
    Route::get('/test/result', 'testController@result');

    Route::get('/test/result/short','testResultController@short');
    Route::get('/test/result/full','testResultController@full');
});