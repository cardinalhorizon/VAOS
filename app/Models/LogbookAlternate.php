<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LogbookAlternate
 *
 * @mixin \Eloquent
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
