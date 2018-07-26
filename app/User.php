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

    public function pirep()
    {
        return $this->hasMany('App\Models\Flight');
    }

    public function group()
    {
        return $this->belongsToMany('App\Models\Group');
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
