<?php

namespace App\Actions;

use DB;
use App\Round;
use App\Draw;

class GetNextUp
{
  public function do(Round $round, $side)
  {

    $query_object = DB::select (DB::raw("SELECT draws.id
                                FROM draws JOIN allocations on draws.team_id = allocations.team_id
                                WHERE round_id = '$round->id'
                                AND side = '$side'
                                AND status = 0
                                ORDER BY position
                                LIMIT 1"));
    
    return Draw::find($query_object[0]->id);
  }
}