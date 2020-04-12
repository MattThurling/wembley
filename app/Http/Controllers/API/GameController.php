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
                      ->with('user')
                      ->first();

    $allocations = Player::find($player->id)
                            ->allocations
                            ->where('status', '!=', -1)
                            ->load('team', 'team.division');

    $phase = 'round';
    $round = $tournament->round;
    $home_team = null;
    $away_team = null;
    $home_user = null;
    $away_user = null;
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


      // TODO Refactor the current user function, doesn't belong in team model
      $home_user = $home_team->current_user($tournament)->first();
      $away_user = $away_team->current_user($tournament)->first();

      
      // Check for unallocated teams
      if (!$away_user) {
        $phase = 'bid';
        $bid_side = 'away';
      } 

      // Override so home is first
      if (!$home_user) {
        $phase = 'bid';
        $bid_side = 'home';
      }

      if ($bid_side) {
        $high_bid = $this->highBid($tournament);

        if ($high_bid) {
          $high_bidder_name = $high_bid->player->user->name;
          $high_bid_amount = $high_bid->amount;
        }
      }


      // Check for teams owned by same player - and check they're not just both unallocated
      if ($home_user && $home_user == $away_user) {
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
          $signature = $round->id . $round->position . $phase;
          $message = 'Teams are owned by the same player. Choose who plays!';
          $this->systemChat->do($tournament, $message, $signature);
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

    $tournament->players->load('user');

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
            'match' => $match,
            'bid_side' => $bid_side,
            'high_bid_amount' => $high_bid_amount,
            'high_bidder_name' => $high_bidder_name];

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
      $home = rand(0,5);
      $away = rand(0,5);
      $home_allocation_id = $this->getNextUp->do($round, 1)->team->allocation($tournament)->first()->id;
      $away_allocation_id = $this->getNextUp->do($round, 0)->team->allocation($tournament)->first()->id;
      $match = Match::create([
              'round_id' => $round->id,
              'position' => $round->position,
              'home_allocation_id' => $home_allocation_id,
              'away_allocation_id' => $away_allocation_id,
              'home_score' => $home,
              'away_score' => $away]);

      broadcast(new UpdateTournamentEvent($tournament->id));
  }


  // Move on to the next draw of the round
  public function next(Tournament $tournament)
  {
      $round = $tournament->round;

      // Process results of last match
      $match = $round->matches->where('position', $round->position)->first();

      $gate = $match->home_allocation->team->gate;

      // For now, away team goes through if it's a draw
      // TODO big refactor when adding proper game logic
      if ($match->away_score >= $match->home_score) {
        $match->home_allocation->update(['status' => -1]);
        $match->home_allocation->player->balance += $gate / 3;
        $match->away_allocation->update(['status' => 1]);
        $match->away_allocation->player->balance += 2 * $gate / 3;
      } else {
        $match->home_allocation->update(['status' => 1]);
        $match->home_allocation->player->balance += 2 * $gate / 3;
        $match->away_allocation->update(['status' => -1]);
        $match->away_allocation->player->balance += $gate / 3;
      }

      $match->home_allocation->player->save();
      $match->away_allocation->player->save();

      // Increment the round position
      if ($round->position < $round->number_of_matches) {
          $round->position++;
          $round->save();
          broadcast(new UpdateTournamentEvent($tournament->id));
      } else {
          $next_matches = $round->number_of_matches/2;
          $name = $this->getRoundName($next_matches);
          $this->round($tournament, $next_matches, $name);
      }
      
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


}
