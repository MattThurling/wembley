<?php

namespace App;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'balance'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'created_at', 'updated_at', 'tournament_id', 'user_id'
    ];

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function allocations()
    {
      return $this->hasMany('App\Allocation');
    }

    public function stars()
    {
        return $this->belongsToMany('App\Star')->withPivot('play');
    }

    public function tournament()
    {
        return $this->belongsTo('App\Tournament');
    }

}
