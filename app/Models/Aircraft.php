<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    protected $table = 'aircraft';

    public function hub()
    {
        return $this->belongsTo('App\Models\Hub');
    }
    public function location()
    {
        return $this->belongsTo('App\Models\Airport');
    }
    public function airline()
    {
        return $this->belongsTo('App\Models\Airline');
    }
    public function aircraft_group()
    {
        return $this->belongsToMany('App\Models\AircraftGroup', 'aircraft_group_pivot');
    }
}
