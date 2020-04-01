<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Draw extends Model
{
  public function team()
  {
    return $this->belongsTo('App\Team');
  }

}
