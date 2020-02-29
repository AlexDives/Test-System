<?php

Route::get('/', 'indexController@index');
Route::post('/auth', 'Ajax\authController@auth');
Route::get('/quit', 'Ajax\authController@quit');

Route::get('/editor', 'editorController@main')->middleware('authCheck');

Route::post('/speedrequest', 'Ajax\requestController@req')->middleware('authCheck');
Route::post('/editor/search', 'editorController@searchTest')->middleware('authCheck');

Route::get('/info', 'editorController@info')->middleware('authCheck');
Route::post('/info/save', 'editorController@saveTestInfo')->middleware('authCheck');
Route::get('/info/new', 'editorController@newTest')->middleware('authCheck');
Route::get('/info/delete', 'editorController@deleteTest')->middleware('authCheck');
Route::get('/info/rollback', 'editorController@rollbackTest')->middleware('authCheck');
Route::post('/info/editors', 'editorController@testEditors')->middleware('authCheck');
Route::post('/info/saveTestEditors', 'editorController@saveTestEditors')->middleware('authCheck');

Route::get('/questions', 'editorController@questions')->middleware('authCheck');
Route::post('/questions/save', 'editorController@saveQuestions')->middleware('authCheck');
Route::post('/questions/speedFillQuest', 'editorController@speedFillQuest')->middleware('authCheck');
Route::post('/questions/loadList', 'editorController@loadQuestList')->middleware('authCheck');
Route::post('/questions/selectedQuest', 'editorController@selectedQuest')->middleware('authCheck');
Route::post('/questions/deleteQuest', 'editorController@deleteQuest')->middleware('authCheck');

Route::get('/manager', 'managerController@main')->middleware('authCheck');
Route::get('/manager/loadUserTable', 'managerController@loadUserTable')->middleware('authCheck');
Route::post('/manager/save', 'managerController@saveUser')->middleware('authCheck');
Route::post('/manager/delete', 'managerController@deleteUser')->middleware('authCheck');

Route::get('/statistic', 'statisticController@main')->middleware('authCheck');
Route::post('/statistic/refresh', 'statisticController@loadStats')->middleware('authCheck');

Route::get('/registration', 'Ajax\regTestsController@index');
Route::post('/registration/post', 'Ajax\regTestsController@registration');
Route::post('/Check_login', 'Ajax\regTestsController@check_login');
Route::post('/Check_email', 'Ajax\regTestsController@check_email');
Route::get('/verificate', 'Ajax\regTestsController@verificate');

Route::get('/pers/list', 'persController@persList')->middleware('authCheck');
Route::post('/pers/check', 'persController@persCheck')->middleware('authCheck');
Route::get('/pers/cabinet', 'persController@persCabinet')->middleware('authCheck');

Route::get('/persons/trialTesting', 'trialTestingController@index');

Route::get('/test/start', 'testController@start')->middleware('authCheck');
Route::post('/test/loadQuestList', 'testController@loadQuestList')->middleware('authCheck');
Route::post('/test/selectedQuest', 'testController@selectedQuest')->middleware('authCheck');
Route::post('/test/confirmResponse', 'testController@confirmResponse')->middleware('authCheck');
Route::post('/test/timeLeft', 'testController@timeLeft')->middleware('authCheck');
Route::get('/test/result', 'testController@result')->middleware('authCheck');