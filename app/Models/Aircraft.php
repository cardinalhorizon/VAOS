<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    protected $table = 'aircraft';

    public function base()
    {
        return $this->belongsTo('App\Models\Base');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location');
    }

    public function airline()
    {
        return $this->belongsTo('App\Models\AviationGroup');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function aircraft_group()
    {
        return $this->belongsToMany('App\Models\AircraftGroup', 'aircraft_group_pivot');
    }

    public function type_rating()
    {
        return $this->belongsTo('App\Models\TypeRating');
    }

    public function flights()
    {
        return $this->hasMany('App\Models\Flight');
    }

    public function isAvailable()
    {
        $active = $this->flights()->filed()->active()->get();
        if ($active->isEmpty()) {
            return true;
        }

        return false;
    }
}
