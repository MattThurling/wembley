<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use App\Tournament;
use DB;

class Team extends Model
{
  // Custom attributes
  protected $appends = ['name'];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'fullname', 'shortname', 'nickname', 'shirt_colour', 'shorts_colour'
  ];

  // Gets the user behind the player who currently owns the team TODO - don't think this is used and it doesn't belong here
  public function current_user(Tournament $tournament)
  {
    $user = DB::table('allocations')
                      ->join('players', 'allocations.player_id', '=', 'players.id')
                      ->join('tournaments', 'players.tournament_id', '=', 'tournaments.id')
                      ->join('teams', 'allocations.team_id', '=', 'teams.id')
                      ->join('users', 'players.user_id', '=', 'users.id')
                      ->select('users.*')
                      ->where('teams.id', $this->id)
                      ->where('tournaments.id', $tournament->id);

    return $user;
  }

  public function allocation(Tournament $tournament)
  {
    // Gets the linking record of the team the player is allocated to in the current tournament TODO - don't think this is used and it doesn't belong here
    $allocation = DB::table('allocations')
                      ->join('players', 'allocations.player_id', '=', 'players.id')
                      ->join('tournaments', 'players.tournament_id', '=', 'tournaments.id')
                      ->join('teams', 'allocations.team_id', '=', 'teams.id')
                      ->select('allocations.*')
                      ->where('teams.id', $this->id)
                      ->where('tournaments.id', $tournament->id);

    return $allocation;
  }

  public function getNameAttribute () 
  {
    return $this->{config('app.team_name_column')};
  }

  public function division ()
  {
    return $this->belongsTo('App\Division');
  }
}
