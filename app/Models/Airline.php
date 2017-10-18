<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    public $timestamps = false;

    protected $fillable = ['icao', 'icao','fshub_id', 'iata', 'name', 'logo', 'widget', 'callsign'];

    public function hubs()
    {
        return $this->hasMany('App\Models\Hub');
    }
}
