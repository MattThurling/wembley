<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tournament;

class Bid extends Model
{
   /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'amount', 'team_id', 'round_id', 'position', 'player_id'
  ];


  public function player () 
  {
  	return $this->belongsTo('App\Player');
  }
}
