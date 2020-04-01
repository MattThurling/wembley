<?php

namespace App\Http\Controllers\Web;

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

    // Default player view for the current tournament
    public function show (Tournament $tournament)
    {
        $player = Player::where('user_id', Auth::id())
                            ->where('tournament_id', $tournament->id)
                            ->with('user')
                            ->first();

        $allocations = Player::find($player->id)->allocations->load('team');

        return view('tournament', compact('tournament', 'player', 'allocations'));
    }

}
