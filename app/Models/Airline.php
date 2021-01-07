<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $fillable = [
        'name',
        'iata',
        'icao',
        'callsign',
        'hub_id',
        'color_primary',
        'color_secondary',
        'color_highlight',
        'autoAccept',
        'isAccepting',
        'autoAdd',
        'aaEnabled',
        'aaLandingRate',
    ];
    public $timestamps = false;

    public function hubs()
    {
        return $this->belongsToMany('App\Models\Airport', 'hubs')->withPivot('id');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User')->withPivot('pilot_id', 'status', 'primary', 'admin');
    }
    public function aircraft_groups()
    {
        return $this->hasMany('App\Models\AircraftGroup');
    }
}
