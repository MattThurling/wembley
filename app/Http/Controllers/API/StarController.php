<?php

namespace App\Http\Controllers\API;

use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Star;
use App\Player;
use App\Tournament;
use App\Events\UpdateTournamentEvent;
use App\Actions\GetNextUp;

class StarController extends Controller
{
  // Purchase a star player
  public function buy (Tournament $tournament, Request $request)
  {
    $message = '';
    
    $player = Player::where('user_id', Auth::id())
                      ->where('tournament_id', $tournament->id)
                      ->first();

    if ($player->stars->find($request->input('star_id'))) $message = "Only one star of each type is allowed";

    $star = Star::find($request->input('star_id'));

    if ($star->price > $player->balance) $message = "That's more than you've got!";

    if ($message) return response()->json(['message' => $message], 403);

    $player->stars()->attach($star);
    $player->balance -= $star->price;
    $player->save();
    broadcast(new UpdateTournamentEvent($tournament->id));
  }

  // Choose not to deploy star
  public function rest (Tournament $tournament, Request $request)
  {
    
    $player = Player::where('user_id', Auth::id())
                      ->where('tournament_id', $tournament->id)
                      ->first();

    DB::table('player_star')
          ->where('player_id', $player->id)
          ->where('star_id', $request->input('star_id'))
          ->update(['play' => 0]);

    broadcast(new UpdateTournamentEvent($tournament->id));
  }

  // Choose to deploy star
  public function play (Tournament $tournament, Request $request)
  {
    
    $player = Player::where('user_id', Auth::id())
                      ->where('tournament_id', $tournament->id)
                      ->first();

    DB::table('player_star')
          ->where('player_id', $player->id)
          ->where('star_id', $request->input('star_id'))
          ->update(['play' => 1]);

    broadcast(new UpdateTournamentEvent($tournament->id));
  }
}
