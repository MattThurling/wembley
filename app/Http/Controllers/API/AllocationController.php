<?php

namespace App\Http\Controllers\API;

use Auth;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Player;
use App\Tournament;
use App\Events\UpdateTournamentEvent;
use App\Actions\GetNextUp;
use App\Actions\SendSystemChatOnce;

class AllocationController extends Controller
{
  public $getNextUp;

  public function __construct (GetNextUp $getNextUp, SendSystemChatOnce $systemChat)
  {
    $this->getNextUp = $getNextUp;
    $this->systemChat = $systemChat;
  }

  public function sell (Tournament $tournament, Request $request)
  {

      
    $player = Player::where('user_id', Auth::id())
                      ->where('tournament_id', $tournament->id)
                      ->first();

    $allocation = $player->allocations()
                          ->where('tournament_id', $tournament->id)
                          ->where('team_id', $request->input('team_id'))
                          ->first();

    \Log::info('all', [$allocation]);

    $allocation->update(['player_id' => null]);

    $gate = $this->getNextUp->do($tournament->round, 1)->team->gate;

    $player->balance += 2*$gate/3;

    $message = $allocation->team->nickname . ' sold back to the bank by ' . $player->user->name . ' for £' . number_format(2*$gate/3);
    $signature = 'Sell ' . $allocation->team->name;
    $this->systemChat->do($tournament, $message, $signature);

    broadcast(new UpdateTournamentEvent($tournament->id));
  }
}
