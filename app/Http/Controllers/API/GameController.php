<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;
use App\Http\Controllers\Controller;
use App\Tournament;
use App\Team;
use App\Allocation;
use App\Player;
use App\Round;
use App\Match;
use App\Chat;
use App\Bid;
use App\Star;
use App\Events\ChatEvent;
use App\Events\TournamentStarted;
use App\Events\UpdateTournamentEvent;
use App\Actions\GetNextUp;
use App\Actions\SendSystemChatOnce;

class GameController extends Controller
{

  public $getNextUp;

  public function __construct (GetNextUp $getNextUp, SendSystemChatOnce $systemChat)
  {
    $this->getNextUp = $getNextUp;
    $this->systemChat = $systemChat;
  }


  // Show the information for a given tournament
  public function show (Tournament $tournament)
  {

    $player = Player::where('user_id', Auth::id())
                      ->where('tournament_id', $tournament->id)
                      ->with('user', 'stars')
                      ->first();

    $players = Player::with(['user', 'allocations', 'allocations.team', 'stars'])->get();

    $allocations = Player::find($player->id)
                            ->allocations
                            ->where('status', '!=', -1)
                            ->load('team', 'team.division');

    $message = '';
    $phase = 'round';
    $round = $tournament->round;
    $home_team = null;
    $away_team = null;
    $home_player = null;
    $away_player = null;
    $match = null;
    $bid = null;
    $bid_side = null;
    $high_bid = null;
    $high_bidder_name = 'MINIMUM';
    $high_bid_amount = 10000;

    if ($round) {
      // The initial draw of teams. There may be some shenanigans - auctions and reordering
      // before a match actually takes place
      $phase = 'draw';

      $home_team = $this->getNextUp->do($round, 1)->team->load('division', 'division.odds'); // 1 => home
      $away_team = $this->getNextUp->do($round, 0)->team->load('division', 'division.odds');; // 0 => away

      $home_allocation = $this->getAllocation($tournament, $home_team);
      $away_allocation = $this->getAllocation($tournament, $away_team);

      
      
      // Check for unallocated teams
      if (!$away_allocation) {
        $phase = 'bid';
        $bid_side = 'away';
      } else {
        $away_player = $away_allocation->player->load(['stars', 'user']);
      }

      // Override so home is first
      if (!$home_allocation) {
        $phase = 'bid';
        $bid_side = 'home';
      } else {
        $home_player = $home_allocation->player->load(['stars', 'user']);
      }

      if ($bid_side) {
        $high_bid = $this->highBid($tournament);

        if ($high_bid) {
          $high_bidder_name = $high_bid->player->user->name;
          $high_bid_amount = $high_bid->amount;
        }
      }


      // Check for teams owned by same player - and check they're not just both unallocated
      if ($home_player && $home_player == $away_player) {
        // See if all the remaining teams are owned by the same player
        $remaining_allocations_info = DB::table('allocations')
                                          ->select('player_id', DB::raw('count(*) as total'))
                                          ->where('tournament_id', $tournament->id)
                                          ->where('status', 0)
                                          ->groupBy('player_id')
                                          ->get();
        if ($remaining_allocations_info->count() < 2) {
          $phase = 'sell';
        } else {
          $phase = 'redraw';
          // System messages have a unique signature to prevent duplication
          $signature = $round->id . $round->position . $phase . 'same-owner';
          $message = __('messages.same-owner', ['owner' => $home_player->user->name]);
          
        }
      } else {

        if ($home_player && $away_player) $message = __('messages.boost-help', [
                                        'home_user' => $home_player->user->name,
                                        'away_user' => $away_player->user->name]);

        $signature = $tournament->id . 'boost-help';
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

    $results = Match::all();

    $tournament->players->load('user');

    $owner = false;
    if (Auth::id() == $tournament->owner_id) $owner = true;

    $data = ['tournament' => $tournament,
            'player' => $player,
            'players' => $players,
            'owner' => $owner,
            'allocations' => $allocations,
            'phase' => $phase,
            'round' => $round,
            'home_team' => $home_team,
            'away_team' => $away_team,
            'home_player' => $home_player,
            'away_player' => $away_player,
            'match' => $match,
            'bid_side' => $bid_side,
            'high_bid_amount' => $high_bid_amount,
            'high_bidder_name' => $high_bidder_name,
            'stars' => $this->getStars($player),
            'results' => $this->getResults($tournament)];

    if ($message) $this->systemChat->do($tournament, $message, $signature);
    return response()->json($data);
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

      $round = Round::create([
                  'number_of_matches' => $number_of_matches,
                  'tournament_id' => $tournament->id,
                  'name' => $name]); //TODO: move round names to config or at least constant
      $tournament->update(['round_id' => $round->id]);

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
                              'team_id' => $allocation->team->id,
                              'side' => $side,
                              'position' => $position]);
          // Increment position every other team
          if (!$side) $position++;
          // Flip side of draw
          $side = !$side;
      }
      broadcast(new UpdateTournamentEvent($tournament->id));
  }

  // Start a new match
  public function match(Tournament $tournament)
  {
      $round = $tournament->round;
      // Create a match with the currently drawn teams
      $home_team = $this->getNextUp->do($round, 1)->team;
      $away_team = $this->getNextUp->do($round, 0)->team;

      $home_allocation = $this->getAllocation($tournament, $home_team);
      $away_allocation = $this->getAllocation($tournament, $away_team);

      $match = Match::create([
              'round_id' => $round->id,
              'position' => $round->position,
              'home_allocation_id' => $home_allocation->id,
              'away_allocation_id' => $away_allocation->id,
              'home_score' => $this->generateScore($home_allocation, 'home_odds'),
              'away_score' => $this->generateScore($away_allocation, 'away_odds')]);

      // Was it the final?
      if ($round->number_of_matches == 1) {

        $tournament->status = -1;
        $tournament->save();
      }

      broadcast(new UpdateTournamentEvent($tournament->id));
  }


  // Move on to the next draw of the round
  public function next(Tournament $tournament)
  {
    $message = '';
    $round = $tournament->round;

    // Process results of last match
    $match = $round->matches->where('position', $round->position)->first();

    $gate = $match->home_allocation->team->gate;

    // For now, away team goes through if it's a draw
    if ($match->away_score >= $match->home_score) {
      $this->processMatchResult($match->home_allocation, $gate, 'lose');
      $this->processMatchResult($match->away_allocation, $gate, 'win');    
    } else {
      $this->processMatchResult($match->away_allocation, $gate, 'lose');
      $this->processMatchResult($match->home_allocation, $gate, 'win');   
    }


    // Increment the round position
    if ($round->position < $round->number_of_matches) {
        $round->position++;
        $round->save();
        broadcast(new UpdateTournamentEvent($tournament->id));
    } else {
        $next_matches = $round->number_of_matches/2;
        $name = $this->getRoundName($next_matches);
        $message = "It's the " . $name . '!';
        $signature = $message;
        
        $this->round($tournament, $next_matches, $name);
    }
    
    $message = __('messages.gate-help', ['home_team' => $match->home_allocation->team->name]);
    $signature = $tournament->id . 'gate-help'; // so it only gets sent once
    if ($message) $this->systemChat->do($tournament, $message, $signature);
  }

  // redraw a team and send it to the bottom of the queue
  public function redraw (Tournament $tournament, Request $request) {
    $round = $tournament->round;
    if ($request->side == 'home') {
      $draw = $this->getNextUp->do($round, 1);
    } else {
      $draw = $this->getNextUp->do($round, 0);
    }
    $draw->position += 1000;
    $draw->save();
    broadcast(new UpdateTournamentEvent($tournament->id));
  }

  // Award the auctioned team to the highest bidder
  public function closeAuction (Tournament $tournament)
  {
    $bid = $this->highBid($tournament);
    $player = $bid->player;

    $allocation = Allocation::where('team_id', $bid->team_id)
                              ->where('tournament_id', $tournament->id)
                              ->first();
    $allocation->update(['player_id'=> $player->id]);
    $player->balance -= $bid->amount;
    $player->save();
    Bid::where('round_id', $tournament->round->id)->delete();
    $message = 'Auction closed! ' . $allocation->team->name . ' sold to ' . $player->user->name . ' for £' . number_format($bid->amount);
    $this->systemChat->do($tournament, $message, $message);
    broadcast(new UpdateTournamentEvent($tournament->id));
  }

  // Deal teams at random to players
  private function allocate(Tournament $tournament)
  {
      $players = $tournament->players()->get()->shuffle();
      $teams = Team::all()->shuffle();

      $portfolio_size = intval(25 / count($players));

      // Deal teams to players
      for ( $i = 0; $i < $portfolio_size; $i++ ) {
          foreach ( $players as $player ) {
              $team = $teams->shift(); // Removes a value from the array
              if ($team) Allocation::create([
                                      'player_id' => $player->id,
                                      'team_id' => $team->id,
                                      'tournament_id' => $tournament->id]);
          }
      }

      // Create empty allocations for the remaining teams
      foreach ($teams as $team) {
        Allocation::create(['team_id' => $team->id, 'tournament_id' => $tournament->id]);
      }
  }

  // Get the highest bid for a particular auction
  private function highBid(Tournament $tournament)
  {
    $high = Bid::where('round_id', $tournament->round_id)
                ->where('position', $tournament->round->position)
                ->orderBy('amount', 'desc')
                ->first();
    return $high;
  }

  // Name the rounds
  private function getRoundName ($number_of_matches)
  {
    switch ($number_of_matches) {
      case 1:
        $name = 'Final';
        break;
      case 2:
        $name = 'Semi Finals';
        break;
      case 4:
        $name = 'Quarter Finals';
        break;
      case 8:
        $name = 'Fifth Round';
        break;
      case 16:
        $name = 'Fourth Round';
        break;
    }

    return $name;
  }

  // Get a list of stars and whether the player has them and is playing them
  private function getStars (Player $player)
  {
    $stars = DB::select (DB::raw("SELECT *
                                  FROM stars
                                  LEFT JOIN
                                  (select star_id, play from player_star where player_id = '$player->id') as p 
                                  ON id = star_id"));
    return $stars;
  }

  // Get the current owner of a team
  private function getAllocation (Tournament $tournament, Team $team)
  {
    $allocation = DB::table('allocations')
                      ->join('players', 'allocations.player_id', '=', 'players.id')
                      ->join('tournaments', 'players.tournament_id', '=', 'tournaments.id')
                      ->join('teams', 'allocations.team_id', '=', 'teams.id')
                      ->select('allocations.id')
                      ->where('teams.id', $team->id)
                      ->where('tournaments.id', $tournament->id)
                      ->first();

    if ($allocation) return Allocation::find($allocation->id);
  }

  // Pay gate, update team status, keep or lose stars
  private function processMatchResult (Allocation $allocation, $gate, $result)
  {
    if ($result == 'lose') {
      $allocation->update(['status' => -1]);
      $allocation->player->balance += $gate / 3;
      DB::table('player_star')
          ->where('player_id', $allocation->player->id)
          ->where('play', true)
          ->delete();
    } else {
      $allocation->update(['status' => 1]);
      $allocation->player->balance += 2 * $gate / 3;
      DB::table('player_star')
          ->where('player_id', $allocation->player->id)
          ->where('play', true)
          ->update(['play' => 0]);
    }
    $allocation->player->save();
  }

  // Generate a weighted random score
  private function generateScore(Allocation $allocation, $field)
  {
    // Boost, based on player's active stars
    $boost = $allocation->player->stars()->wherePivot('play', true)->count();
    \Log::info('boost', [$boost]);
    // Score, based on division odds
    $odds = $allocation->team->division->odds;
    $total_weight = $odds->sum($field);

    $random_number = rand(1, $total_weight);
    $counter = $total_weight;
    foreach ($odds as $odd) {
      $counter -= $odd->{$field};
      if ($random_number > $counter) {
        return $odd->goals + $boost;
      }
    }
  }

  // Previous matches
  private function getResults (Tournament $tournament)
  {
    $results = [];
    foreach ($tournament->rounds()->orderBy('number_of_matches')->get() as $round) {
      $stage = ['name' => $this->getRoundName($round->number_of_matches)];
      $rubbers = [];
      foreach ($round->matches()->orderBy('position', 'desc')->get() as $match) {
        $result = [
          'home_team_name' => $match->home_allocation->team->name,
          'home_score' => $match->home_score,
          'away_team_name' => $match->away_allocation->team->name,
          'away_score' => $match->away_score,
        ];
        array_push($rubbers, $result);
      }
      $stage['rubbers'] = $rubbers;
      array_push($results, $stage);
    }
    return $results;
  }

}
