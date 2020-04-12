<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    public function odds ()
    {
      return $this->hasMany('App\Odd', 'division_id', 'level');
    }
}
