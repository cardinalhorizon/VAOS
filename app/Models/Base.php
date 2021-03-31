<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    public function airport()
    {
        return $this->belongsTo('App\Models\Airport');
    }

    public function airline()
    {
        return $this->belongsTo('App\Models\AviationGroup');
    }

    public function user()
    {
        return $this->hasMany('App\Models\User');
    }
}
