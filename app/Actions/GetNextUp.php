<?php

namespace App\Actions;

use DB;
use App\Round;
use App\Draw;

class GetNextUp
{
  public function do(Round $round, $side)
  {
    $draw = DB::table('draws')
                      ->join('allocations', 'draws.team_id', '=', 'allocations.team_id')
                      ->where('round_id', $round->id)
                      ->where('status', 0)
                      ->where('side', $side)
                      ->orderBy('position')
                      ->first();

    return Draw::find($draw->id);
  }
}