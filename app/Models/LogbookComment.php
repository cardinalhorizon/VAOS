<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightComment extends Model
{

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function flight()
    {
        return $this->belongsTo('App\Models\Flight');
    }
}
