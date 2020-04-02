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

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function allocations()
    {
      return $this->hasMany('App\Allocation');
    }

}
