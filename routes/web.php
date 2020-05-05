<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('questions/{question}/check', 'QuestionsController@checkAnswer')->name('questions.check');
Route::get('questions/{question}/help','QuestionsController@getHelp')->name('questions.help');

Route::middleware(['auth','role:admin'])->group(function (){
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::resource('users', 'UsersController');
    Route::resource('questionnaires', 'QuestionnairesController');
    Route::resource('questions', 'QuestionsController');

    //DataTables
    Route::get('get_users_datatables', 'UsersController@getUsersDataTable')->name('getUsers');
    Route::get('get_questionnaires_datatables', 'QuestionnairesController@getQuestionnairesDataTable')->name('getQuestionnaires');
    Route::get('get_questions_datatables', 'QuestionsController@getQuestionsDataTable')->name('getQuestions');
});
