<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'password', 'vatsim', 'ivao', 'status', 'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function flights()
    {
        return $this->hasMany('App\Models\Flight');
    }

    public function group()
    {
        return $this->belongsToMany('App\Models\Group');
    }

    /*
     * COMPUTED PROPERTIES
     */

    public function avgLandingRate()
    {
        return $this->flights()
            ->selectRaw('avg(landingrate) as aggregate, user_id')
            ->groupBy('user_id');
    }

    public function getAvgLandingRateAttribute()
    {
        if ( ! array_key_exists('avgLandingRate', $this->relations)) {
            $this->load('avgLandingRate');
        }

        $relation = $this->getRelation('avgLandingRate')->first();

        return ($relation) ? $relation->aggregate : null;
    }

    public function totalFlightTime()
    {
        return $this->flights()
            ->selectRaw('sum(flighttime) as aggregate, user_id')
            ->groupBy('user_id');
    }

    public function getTotalFlightTimeAttribute()
    {
        if ( ! array_key_exists('totalFlightTime', $this->relations)) {
            $this->load('totalFlightTime');
        }

        $relation = $this->getRelation('totalFlightTime')->first();

        return ($relation) ? $relation->aggregate : null;
    }

    public function totalFlights()
    {
        return $this->flights()
            ->selectRaw('count(*) as aggregate, user_id')
            ->groupBy('user_id');
    }

    public function getTotalFlightsAttribute()
    {
        if ( ! array_key_exists('totalFlights', $this->relations)) {
            $this->load('totalFlights');
        }

        $relation = $this->getRelation('totalFlights')->first();

        return ($relation) ? $relation->aggregate : null;
    }

    public function airlines()
    {
        return $this->belongsToMany('App\Models\Airline')->withPivot('pilot_id', 'status', 'primary', 'admin');
    }

    public function hasAirline($airline)
    {
        return $this->airlines->contains($airline);
    }

}
