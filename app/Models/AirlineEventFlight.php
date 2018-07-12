<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirlineEventFlight extends Model
{
    public function airline()
    {
        return $this->belongsTo('App\Models\Airline');
    }
    public function depapt()
    {
        return $this->belongsTo('App\Models\Airport');
    }
    public function arrapt()
    {
        return $this->belongsTo('App\Models\Airport');
    }
    public function aircraft_group()
    {
        return $this->belongsToMany('App\Models\AircraftGroup');
    }
    public function aircraft()
    {
        return $this->belongsToMany('App\Models\Aircraft');
    }
}
