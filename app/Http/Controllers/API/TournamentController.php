<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Carbon\Carbon;
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
    $tournaments = Tournament::all()->load('owner','players','players.user');
    return response()->json(['data' => $tournaments]);
  }

  // Show the information for a given tournament
  public function show (Tournament $tournament)
  {
    $player = Player::where('user_id', Auth::id())
                      ->where('tournament_id', $tournament->id)
                      ->with('user')
                      ->first();

    $allocations = Player::find($player->id)->allocations->load('team', 'team.division');

    $phase = 'round';
    $round = $tournament->round;
    $home_team = null;
    $away_team = null;
    $home_user = null;
    $away_user = null;
    $match = null;

    if ($round) {
      // The initial draw of teams. There may be some shenanigans - auctions and reordering
      // before a match actually takes place
      $phase = 'draw';
      // TODO get the home relationship to return like normal eloquent relationship
      $home_team = $round->home()->team;
      $away_team = $round->away()->team;
      $home_user = $round->home()->team->current_user($tournament)->first();
      $away_user = $round->away()->team->current_user($tournament)->first();
      if ($home_user == $away_user) {
        $phase = 'redraw';
        // Emit system message from the owner user so it only goes once
        if (Auth::id() == $tournament->owner_id) {
          // System messages have a unique signature to prevent duplication
          $sig = $round->id . $round->position . $phase;

          if (!Chat::where('system_signature', $sig)->count()) {
            $chat = Auth::user()
                      ->messages()
                      ->create([
                        'message' => 'Teams are owned by the same player. Choose who plays!',
                        'tournament_id' => $tournament->id,
                        'system_signature' => $sig]);

            broadcast(new ChatEvent($chat->load('user')));
          }
        }
      }
      // A match is not created until the Play button is pressed
      $match = Match::where('round_id', $round->id)
              ->where('position', $round->position)
              ->first();
      if ($match) {
        $phase = 'match';
        // Make sure relationships are loaded
        $match->home_allocation->team;
        $match->away_allocation->team;
        $match->home_allocation->player->user;
        $match->away_allocation->player->user;
      }
    }

    $owner = false;
    if (Auth::id() == $tournament->owner_id) $owner = true;

    $data = ['tournament' => $tournament,
            'player' => $player,
            'owner' => $owner,
            'allocations' => $allocations,
            'phase' => $phase,
            'round' => $round,
            'home_team' => $home_team,
            'away_team' => $away_team,
            'home_user' => $home_user,
            'away_user' => $away_user,
            'match' => $match];

    return response()->json($data);
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
      $tournament->players()->create(['user_id' => Auth::id(), 'balance' => 12500]);
      broadcast(new TournamentJoined($tournament));
      return 'Success';
  }

  // Start the tournament by allocating teams to the players
  public function start(Tournament $tournament)
  {
      $this->allocate($tournament);
      $tournament->status = 1;
      $tournament->save();
      broadcast(new TournamentStarted($tournament));
      return ['message' => 'Success'];
  }

  // Send the players to the tournament once the dealer has started it
  public function goTo(Tournament $tournament)
  {
    if ($tournament->players->where('user_id', Auth::id())->count()) {
      return ['message' => true];
    } else {
      return ['message' => false];
    }
  }

  // Start a new round
  public function round(Tournament $tournament, $number_of_matches=16, $name='Fourth Round')
  {
      // $number_of_matches = 2 ** $tournament->number_of_rounds;
      $round = Round::create([
                  'number_of_matches' => $number_of_matches,
                  'tournament_id' => $tournament->id,
                  'name' => $name]); //TODO: move round names to config or at least constant
      $tournament->update(['round_id' => $round->id]);
      // This won't work when some of the teams aren't allocated in the first round
      // Break into two functions
      $allocations = Allocation::where('status', '!=', -1)
                                  ->where('tournament_id', $tournament->id)
                                  ->get()
                                  ->shuffle();

      $side = true;
      $position = 1;
      foreach ($allocations as $allocation) {
          // Reset the allocation's status
          $allocation->update(['status' => 0]);
          $round->draws()->create([
                              'team_id' => $allocation->team()->first()->id,
                              'side' => $side,
                              'position' => $position]);
          // Increment position every other team
          if (!$side) $position++;
          // Flip side of draw
          $side = !$side;
      }
      broadcast(new UpdateTournamentEvent($tournament->id));
      return $this->show($tournament);
  }

  // Start a new match
  public function match(Tournament $tournament)
  {
      $round = $tournament->round;
      // Create a match with the currently drawn teams
      // For now, scores are fixed so home team always wins
      $home = rand(0,5);
      $away = rand(0,5);
      $home_allocation_id = $round->home()->team->allocation($tournament)->first()->id;
      $away_allocation_id = $round->away()->team->allocation($tournament)->first()->id;
      $match = Match::create([
              'round_id' => $round->id,
              'position' => $round->position,
              'home_allocation_id' => $home_allocation_id,
              'away_allocation_id' => $away_allocation_id,
              'home_score' => $home,
              'away_score' => $away]);

      // For now, away team goes through if it's a draw
      // Bit of a hack to get the allocations - could be improved
      $home_allocation = Allocation::find($home_allocation_id);
      $away_allocation = Allocation::find($away_allocation_id);

      $gate = $home_allocation->team->gate;

      // TODO big refactor when adding proper game logic
      if ($away >= $home) {
          $home_allocation->update(['status' => -1]);
          $home_allocation->player->balance += $gate / 3;
          $away_allocation->update(['status' => 1]);
          $away_allocation->player->balance += 2 * $gate / 3;
      } else {
          $home_allocation->update(['status' => 1]);
          $home_allocation->player->balance += 2 * $gate / 3;
          $away_allocation->update(['status' => -1]);
          $away_allocation->player->balance += $gate / 3;
      }
      $home_allocation->player->save();
      $away_allocation->player->save();
      broadcast(new UpdateTournamentEvent($tournament->id));
      // TODO Do we need to return anything or just call it?
      return $this->show($tournament);
  }




  // Move on to the next draw of the round
  public function next(Tournament $tournament)
  {
      $round = $tournament->round;

      // Increment the round position
      if ($round->position < $round->number_of_matches) {
          $round->position++;
          $round->save();
      } else {
          $next_matches = $round->number_of_matches/2;
          return $this->round($tournament, $next_matches, 'Fifth Round');
      }
      broadcast(new UpdateTournamentEvent($tournament->id));
      return $this->show($tournament);
  }

  // redraw a team and send it to the bottom of the queue
  public function redraw (Tournament $tournament, Request $request) {
    if ($request->side == 'home') {
      $draw = $tournament->round->home();
    } else {
      $draw = $tournament->round->away();
    }
    $draw->position += 1000;
    $draw->save();
    broadcast(new UpdateTournamentEvent($tournament->id));
    return $this->show($tournament);
  }

  // Deal teams at random to players
  private function allocate(Tournament $tournament)
  {
      $players = $tournament->players()->get()->shuffle();
      $teams = Team::all()->shuffle();

      // $portfolio_size = intval(25 / count($players));
      // Hack for now so all teams are allocated
      $portfolio_size = count($teams);

      for ( $i = 0; $i < $portfolio_size; $i++ ) {
          foreach ( $players as $player ) {
              $team = $teams->shift();
              if ($team) Allocation::create([
                                      'player_id' => $player->id,
                                      'team_id' => $team->id,
                                      'tournament_id' => $tournament->id]);
          }
      }
  }
}
