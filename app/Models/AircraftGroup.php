<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AircraftGroup extends Model
{
    //
    public $table = 'aircraft_groups';

    protected $fillable = ['name', 'icao', 'userdefined'];

    public function aircraft()
    {
        return $this->belongsToMany('App\Models\Aircraft', 'aircraft_group_pivot');
    }

    public function airline()
    {
        return $this->belongsTo('App\Models\AviationGroup');
    }

    public function schedule()
    {
        return $this->belongsToMany('App\Models\Schedule');
    }

    public function isAvailable($aircraft_id)
    {
    }
}
