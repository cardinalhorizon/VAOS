<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LogbookAlternate
 *
 * @mixin \Eloquent
 * @property-read \App\Models\Airport $airport
 * @property-read \App\Models\LogbookEntry $logbookentry
 */
class LogbookAlternate extends Model
{
    protected $fillable = [];

    public function logbookentry()
    {
        return $this->belongsTo('App\Models\LogbookEntry');
    }

    public function airport()
    {
        return $this->belongsTo('App\Models\Airport');
    }
}
