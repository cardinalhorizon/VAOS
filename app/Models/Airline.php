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
        'aaLandingRate'
    ];
    public $timestamps = false;

    public function hub()
    {
        return $this->hasMany('App\Models\Hub');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('pilot_id', 'status', 'primary', 'admin');
    }
}
