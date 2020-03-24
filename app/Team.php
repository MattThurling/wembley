<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Team extends Model
{
    public function player()
    {
      return $this->belongsTo('App\Player');
    }
}
