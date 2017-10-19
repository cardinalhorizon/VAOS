<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Aircraft
 *
 * @mixin \Eloquent
 */
class Aircraft extends Model
{
    protected $fillable = ['icao', 'type', 'name', 'manufacturer', 'registration', 'status', 'hub_id', 'location_id', 'airline_id', 'notes' ];

    public function hub(){
        $this->belongsTo('App\Models\Hub');
    }

    public function airline(){
        $this->belongsTo('App\Models\Airline');
    }

    public function location(){
        $this->belongsTo('App\Models\Airport');
    }

    public function aircraft_group(){
        $this->belongsToMany('App\Models\AircraftGroup', 'aircraft_group_pivot');
    }
}
