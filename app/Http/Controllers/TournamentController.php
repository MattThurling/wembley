<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Tournament;
use App\Team;
use App\Allocation;
use App\Player;
use App\Round;

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
        $tournament = Tournament::create(['owner_id' => Auth::id(), 'number_of_rounds' => 4]);
        // Also join the tournament as a player
        return $this->join($tournament);
    }


    public function join(Tournament $tournament)
    {
        $tournament->players()->create(['user_id' => Auth::id()]);
        return redirect('home');
    }

    public function start(Tournament $tournament)
    {
        $this->allocate();
        return $this->show($tournament);
    }

    public function round(Tournament $tournament)
    {
        $number_of_matches = 2 ** $tournament->number_of_rounds;
        Round::create([
                'number_of_matches' => $number_of_matches,
                'tournament_id' => $tournament->id,
                'name' => 'Fourth Round']); //TODO: move round names to config or at least constant
    }

    public function show(Tournament $tournament)
    {
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
