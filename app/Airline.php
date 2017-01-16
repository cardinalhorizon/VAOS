<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $fillable = [
        'name',
        'iata',
        'icao',
        'callsign',
        'hub_id'
    ];
    public $timestamps = false;

    public function hub()
    {
        return $this->hasMany('Hub');
    }
}
