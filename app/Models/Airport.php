<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    //
    public $timestamps = false;

    public function schedules()
    {
        return $this->hasMany('App\ScheduleTemplate');
    }

}
