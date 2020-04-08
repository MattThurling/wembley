<?php

namespace App\Http\Controllers\API;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bid;
use App\Player;
use App\Tournament;
use App\Events\UpdateTournamentEvent;
use App\Actions\GetNextUp;

class BidController extends Controller
{

  public $getNextUp;

  public function __construct (GetNextUp $getNextUp)
  {
    $this->getNextUp = $getNextUp;
  }

  public function store (Tournament $tournament, Request $request)
  {
    $message = '';
    $amount = $request->input('amount');
    $high = Bid::where('round_id', $tournament->round_id)
                ->where('position', $tournament->round->position)
                ->orderBy('amount', 'desc')
                ->first();

    if ($amount < 1000) $message = 'Minimum bid is £1,000';

    if ($high) {
      if ($amount < $high->amount + 1000) $message = 'Bid must be at least £1,000 more than highest bid';
    }

    if ($message) return response()->json(['message' => $message], 403);

    $round = $tournament->round;

    if ($request->input('side') == 'home') {
      $team = $this->getNextUp->do($round, 1)->team;
    } else {
      $team = $this->getNextUp->do($round, 0)->team;
    }

    $player = Player::where('user_id', Auth::id())
                      ->where('tournament_id', $tournament->id)
                      ->first();

    Bid::create([
          'amount' => $amount,
          'team_id' => $team->id,
          'round_id' => $tournament->round_id,
          'position' => $tournament->round->position,
          'player_id' => $player->id,
          'team_id' => $team->id]);

    broadcast(new UpdateTournamentEvent($tournament->id));
  }
}
