<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleTemplate extends Model
{
    public $table = "schedule_templates";

    public function airline()
    {
        return $this->belongsTo('App\Airline');
    }
    public function depapt()
    {
        return $this->belongsTo('App\Models\Airport');
    }
    public function arrapt()
    {
        return $this->belongsTo('App\Models\Airport');
    }
    public function aircraft_group()
    {
        return $this->belongsTo('App\AircraftGroup');
    }
    // Eloquent Eger Loading Helper
    public static function allFK()
    {
        return with('depicao')->with('arricao')->with('airline')->with('aircraft_group')->get();
    }
}
