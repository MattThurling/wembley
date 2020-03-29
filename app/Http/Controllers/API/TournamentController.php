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

class TournamentController extends Controller
{
  public function show (Tournament $tournament)
  {
    $player = Player::where('user_id', Auth::id())
                            ->where('tournament_id', $tournament->id)
                            ->with('user')
                            ->first();
    return response()->json($player);
  }

}

                            
  // $allocations = Player::find($player->id)->allocations->load('team');
  // $datakeys = ['tournament', 'player', 'allocations'];
  // $view = 'tournament.round';
  // $round = $tournament->round;
  // if ($round) {
  //     // The initial draw of teams. There may be some shenanigans - auctions and reordering
  //     // before a match actually takes place
  //     $home = $round->home()->team;
  //     $away = $round->away()->team;
  //     array_push($datakeys, ['round', 'home', 'away']);
  //     $view = 'tournament.match';
  //     // A match is not created until the Play button is pressed
  //     $match = Match::where('round_id', $round->id)
  //             ->where('position', $round->position)
  //             ->first();

  //     if ($match) {
  //         array_push($datakeys, ['match']);
  //         $view = 'tournament.result';
  //     };
  // }
