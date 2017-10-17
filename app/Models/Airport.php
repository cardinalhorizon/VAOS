<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    public $timestamps = false;

    protected $fillable = ['id', 'name', 'city', 'county', 'iata','icao', 'lat', 'lon'];

        return $this->hasMany('App\Models\Schedules');
    }
}
