<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    public $table = 'flights';

    protected $guarded = [
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function airline()
    {
        return $this->belongsTo('App\Models\Airline');
    }

    public function depapt()
    {
        return $this->belongsTo('App\Models\Airport');
    }

    public function arrapt()
    {
        return $this->belongsTo('App\Models\Airport');
    }

    public function aircraft()
    {
        return $this->belongsTo('App\Models\Aircraft');
    }

    public function flight_data()
    {
        return $this->hasMany('App\Models\ACARSData');
    }

    public function scopeFiled($query)
    {
        return $query->where('state', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('state', 1);
    }

    public function scopeCompleted($query)
    {
        return $query->where('state', 2);
    }
}
