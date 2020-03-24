<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Allocation extends Model
{
    public function team()
    {
      return $this->belongsTo('App\Team');
    }

    public function player()
    {
      return $this->belongsTo('App\Player');
    } 
}

