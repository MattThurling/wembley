<?php

namespace App\Actions;

use DB;
use App\Round;
use App\Draw;
use App\Odd;

class GetOdds
{
  public function do(Draw $draw)
  {
    $division = $draw->team->division->level;

    $odds = [];

    for ($i = 0; $i < 5; $i++) {
      $row =  Odd::where('goals', $i)->where('division_id', $division)->first();
      if ($draw->side) {
        $odd = $row->home_odds;
      } else {
        $odd = $row->away_odds;
      }
      array_push($odds, $odd);
    }

    return $odds;
  }
}