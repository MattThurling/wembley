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

    public function round()
    {
      return $this->hasOne('App\Round');
    }
}
