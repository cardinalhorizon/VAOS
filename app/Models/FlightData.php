<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightData extends Model
{
    protected $table   = 'flight_data';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function flight()
    {
        return $this->belongsTo('App\Models\Flight');
    }
}
