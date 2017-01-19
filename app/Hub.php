<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hub extends Model
{
    public function airport()
    {
        return $this->hasOne('Airport');
    }
    public function airline()
    {
        return $this->belongsTo('Airline');
    }
}
