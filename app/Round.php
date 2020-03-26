<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Round extends Model
{
  public function draws()
  {
    return $this->hasMany('App\Draw');
  }

  public function home()
  {
    return $this->draws()
                    ->where('side', 1)
                    ->where('position', '>=', $this->position)
                    ->orderBy('position')
                    ->first();
  }

  public function away()
  {
    return $this->draws()
                    ->where('side', 0)
                    ->where('position', '>=', $this->position)
                    ->orderBy('position')
                    ->first();
  }
}
