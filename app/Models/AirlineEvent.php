<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirlineEvent extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('status');
    }
    public function airline()
    {
        return $this->belongsTo('App\Models\Airline');
    }
    public function flights()
    {
        return $this->hasMany('App\Models\AirlineEventFlight');
    }
}
