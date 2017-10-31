<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TelemetryPoint.
 *
 * @mixin \Eloquent
 *
 * @property-read \App\Models\Bid $bid
 * @property-read \App\Models\LogbookEntry $logbookentry
 */
class TelemetryPoint extends Model
{
    protected $fillable = [];

    public function bid()
    {
        return $this->belongsTo('App\Models\Bid');
    }

    public function logbookentry()
    {
        return $this->belongsTo('App\Models\LogbookEntry');
    }
}
