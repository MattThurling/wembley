<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Controller;
use App\Tournament;
use App\Team;
use App\Allocation;
use App\Player;
use App\Round;
use App\Match;
use App\Chat;
use App\Events\ChatEvent;
use App\Events\TournamentJoined;
use App\Events\TournamentStarted;
use App\Events\UpdateTournamentEvent;

class TournamentController extends Controller
{
  // Show information for all tournaments
  public function index ()
  {
    $tournaments = Tournament::where('status', 0)->get()->load('owner','players','players.user');

    foreach (Auth::user()->players as $player) {
      $tournament = $player->tournament()->where('status', 1)->first();
      if ($tournament) $tournaments->push($tournament->load('owner','players','players.user'));
    }

    return response()->json(['data' => $tournaments]);
  }

  // Create a new tournament
  public function store(Request $request)
  {
    if (Auth::user()->tournaments->whereIn('status', [0,1])->count() + 1 > env('MAX_TOURNAMENTS')) {
      return ['message' => 'You can only have one active tournament at a time.'];
    } else {
      $tournament = Tournament::create(['owner_id' => Auth::id(), 'number_of_rounds' => 4]);
      // Also join the tournament as a player
      return $this->join($tournament);
    }
  }

  // Add a player to an existing tournament
  public function join(Tournament $tournament)
  {
      $tournament->players()->create(['user_id' => Auth::id(), 'balance' => 1500000]);
      broadcast(new TournamentJoined($tournament));
      return 'Success';
  }

}
