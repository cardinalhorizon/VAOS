<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    //
    public $timestamps = false;

    public function schedule_dep()
    {
        return $this->hasMany('App\Schedule', 'depapt_id');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
