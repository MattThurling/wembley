<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Match extends Model
{
    public function home_allocation()
    {
      return $this->belongsTo('App\Allocation', 'home_allocation_id');
    }

    public function away_allocation()
    {
      return $this->belongsTo('App\Allocation', 'away_allocation_id');
    }
}
