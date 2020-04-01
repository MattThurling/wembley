<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function () {
  Route::get('tournament/{tournament}', 'API\TournamentController@show');
  Route::post('tournament/{tournament}/join', 'API\TournamentController@join');
  Route::post('tournament/{tournament}/start', 'API\TournamentController@start');
  Route::post('tournament/{tournament}/round', 'API\TournamentController@round');
  Route::post('tournament/{tournament}/match', 'API\TournamentController@match');
  Route::post('tournament/{tournament}/next', 'API\TournamentController@next');
  Route::get('tournament', 'API\TournamentController@index');
  Route::post('tournament', 'API\TournamentController@store');
});


