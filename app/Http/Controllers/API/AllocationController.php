<?php

namespace App\Http\Controllers\API;

use Auth;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Player;
use App\Tournament;
use App\Events\UpdateTournamentEvent;

class AllocationController extends Controller
{

  public function sell (Tournament $tournament, Request $request)
  {
    try {
      
    $player = Player::where('user_id', Auth::id())
                      ->where('tournament_id', $tournament->id)
                      ->first();

    $allocation = $player->allocations
                          ->where('tournament_id', $tournament->id)
                          ->where('team_id', $request->input(['team_id']))
                          ->first();

    $allocation->update(['player_id' => null]);

    } catch (Exception $e) {

      return response()->json(['message' => $e->getMessage()], 400);

    }

    broadcast(new UpdateTournamentEvent($tournament->id));
  }
}
