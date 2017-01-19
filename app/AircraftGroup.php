<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AircraftGroup extends Model
{
    //
    public $table = 'aircraft_groups';

    protected $fillable = array('name', 'icao', 'userdefined');

    public function aircraft()
    {
        return $this->belongsToMany('App\Models\Aircraft', 'aircraft_group_pivot');
    }
}
