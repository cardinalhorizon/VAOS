<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = ['id', 'name', 'city', 'country', 'iata', 'icao', 'lat', 'lon', 'data', 'img_irl'];

    protected $casts = [
        'lat' => 'double',
        'lon' => 'double'
    ];
    public $timestamps = false;

    public function schedule_dep()
    {
        return $this->hasMany('App\Schedule', 'depapt_id');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
