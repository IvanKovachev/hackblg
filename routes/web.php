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

Route::get('tasks/all/{filter?}', 'TasksController@all');
Route::resource('tasks', 'TasksController');

Route::get('goals/all/{filter?}', 'GoalsController@all');
Route::resource('goals', 'GoalsController');

Route::group(['prefix' => 'actions'], function () {
    Route::post('doTask', 'ActionsController@doTask');
    Route::post('undoTask', 'ActionsController@undoTask');
    Route::post('completeGoal', 'ActionsController@completeGoal');
    Route::post('uncompleteGoal', 'ActionsController@uncompleteGoal');
    Route::post('depositMoney', 'ActionsController@depositMoney');
    Route::post('withdrawMoney', 'ActionsController@withdrawMoney');
});
