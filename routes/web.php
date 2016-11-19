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

Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('tasks/all', 'TasksController@all');
Route::resource('tasks', 'TasksController');

Route::get('goals/all', 'GoalsController@all');
Route::resource('goals', 'GoalsController');

Route::group(['prefix' => 'actions'], function () {
    Route::post('doTask', 'ActionsController@doTask');
    Route::post('undoTask', 'ActionsController@undoTask');
});
