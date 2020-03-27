<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Tournament extends Model
{
    public function owner()
    {
      return $this->hasOne('App\User', 'id', 'owner_id');
    }

    public function players()
    {
      return $this->hasMany('App\Player');
    }

    // A tournament has many rounds but this function gives the CURRENT round
    public function round()
    {
      return $this->belongsTo('App\Round');
    }
}
