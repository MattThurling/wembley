<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function getCreatedAtAttribute($date)
    {
      return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
    }
}
