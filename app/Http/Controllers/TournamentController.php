<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Tournament;
use App\Team;
use App\Allocation;
use App\Player;

class TournamentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tournament = Tournament::create(['owner_id' => Auth::id()]);
        // Also join the tournament as a player
        return $this->join($tournament->id);
    }


    public function join($id)
    {
        $tournament = Tournament::find($id);
        $tournament->players()->create(['user_id' => Auth::id()]);
        return redirect('home');
    }

    public function start($id)
    {
        $this->allocate();
        return $this->show($id);
    }

    public function show($id)
    {
        $tournament = Tournament::find($id);
        $player = Player::where('user_id', Auth::id())->with('allocations.team')->first();
        return view('tournament', compact(['player']));
    }

    private function allocate()
    {
        $players = Player::all()->shuffle();
        $teams = Team::all()->shuffle();

        $portfolio_size = intval(25 / count($players));

        for ( $i = 0; $i < $portfolio_size; $i++ ) {
            foreach ( $players as $player ) {
                $team = $teams->shift();
                Allocation::create(['player_id' => $player->id, 'team_id' => $team->id]);
            }
        }
    }
}
