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
    return view('welcome');
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');
Route::get('cota', 'BotManController@verificaArray');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('admin/frases-treinamento', 'TrainingPhraseController@index')->name('admin.trainingphrases')->middleware('auth');
Route::get('admin/frases-treinamento/download/{status}', 'TrainingPhraseController@downlaodFilePhrases')->name('admin.downlaodfilephrases')->middleware('auth');
Route::any('admin/frases-treinamento/pesquisar', 'TrainingPhraseController@searchPhrases')->name('admin.searchphrases')->middleware('auth');
