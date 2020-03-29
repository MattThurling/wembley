<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/tournament', 'TournamentController@store');
Route::post('tournament/{tournament}/join', 'TournamentController@join');
Route::post('tournament/{tournament}/start', 'TournamentController@start');
Route::post('tournament/{tournament}/round', 'TournamentController@round');
Route::post('tournament/{tournament}/match', 'TournamentController@match');
Route::post('tournament/{tournament}/next', 'TournamentController@next');
Route::get('tournament/{tournament}', 'Web\TournamentController@show');
Route::get('/chats', 'ChatController@index');
Route::get('/tournament/{tournament}/messages', 'ChatController@fetchAllMessages');
Route::post('/tournament/{tournament}/messages', 'ChatController@sendMessage');
