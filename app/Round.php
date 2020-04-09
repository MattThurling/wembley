<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Round extends Model
{
  public function draws()
  {
    return $this->hasMany('App\Draw');
  }

  public function matches()
  {
    return $this->hasMany('App\Match');
  }

}
