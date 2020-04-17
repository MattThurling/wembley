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
use App\Actions\SendSystemChatOnce;

class BidController extends Controller
{

  public $getNextUp;

  public function __construct (GetNextUp $getNextUp, SendSystemChatOnce $systemChat)
  {
    $this->getNextUp = $getNextUp;
    $this->systemChat = $systemChat;
  }

  public function store (Tournament $tournament, Request $request)
  {

    $player = Player::where('user_id', Auth::id())
                      ->where('tournament_id', $tournament->id)
                      ->first();

    $message = '';

    $amount = $request->input('amount');

    $high = Bid::where('round_id', $tournament->round_id)
                ->where('position', $tournament->round->position)
                ->orderBy('amount', 'desc')
                ->first();

    if ($amount < 10000) $message = 'Minimum bid is £10,000';

    if ($high) {
      if ($amount < $high->amount + 10000) $message = 'Bid must be at least £10,000 more than highest bid';
    }

    if ($amount > $player->balance) $message = "That's more than you've got";

    \Log::info($message);

    if ($message) return response()->json(['message' => $message], 403);

    $round = $tournament->round;

    if ($request->input('side') == 'home') {
      $team = $this->getNextUp->do($round, 1)->team;
    } else {
      $team = $this->getNextUp->do($round, 0)->team;
    }

    

    Bid::create([
          'amount' => $amount,
          'team_id' => $team->id,
          'round_id' => $tournament->round_id,
          'position' => $tournament->round->position,
          'player_id' => $player->id,
          'team_id' => $team->id]);

    $system_message = $player->user->name . ' has bid £' . number_format($amount) . ' for ' . $team->name;

    $this->systemChat->do($tournament, $system_message, $system_message); 

    broadcast(new UpdateTournamentEvent($tournament->id));
  }
}
