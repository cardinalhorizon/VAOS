<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirlineEventFlight extends Model
{
    protected $fillable = ['isGroupFlight', 'airline_id', 'captain_id', 'fo_id', 'flightnum', 'depapt_id', 'arrapt_id', 'route', 'max_users'];
    public $timestamps  = false;

    public function airline()
    {
        return $this->belongsTo('App\Models\AviationGroup');
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
