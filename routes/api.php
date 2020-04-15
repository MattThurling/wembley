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
  
  Route::post('tournament/{tournament}/join', 'API\TournamentController@join');
  Route::post('tournament/{tournament}/start', 'API\GameController@start');
  Route::get('tournament/{tournament}/goto', 'API\GameController@goTo');
  Route::post('tournament/{tournament}/round', 'API\GameController@round');
  Route::post('tournament/{tournament}/redraw', 'API\GameController@redraw');
  Route::post('tournament/{tournament}/match', 'API\GameController@match');
  Route::post('tournament/{tournament}/next', 'API\GameController@next');
  Route::post('tournament/{tournament}/bid', 'API\BidController@store');
  Route::post('tournament/{tournament}/sell', 'API\AllocationController@sell');
  Route::post('tournament/{tournament}/buy-star', 'API\StarController@buy');
  Route::post('tournament/{tournament}/rest-star', 'API\StarController@rest');
  Route::post('tournament/{tournament}/play-star', 'API\StarController@play');
  Route::post('tournament/{tournament}/close-auction', 'API\GameController@closeAuction');
  Route::get('tournament/{tournament}', 'API\GameController@show');

  
  Route::get('tournament/{tournament}/messages', 'API\ChatController@fetchAllMessages');
  Route::post('tournament/{tournament}/messages', 'API\ChatController@sendMessage');

  Route::get('tournament', 'API\TournamentController@index');
  Route::post('tournament', 'API\TournamentController@store');

});


