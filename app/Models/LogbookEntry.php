<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LogbookEntry
 *
 * @mixin \Eloquent
 * @property-read \App\Models\Aircraft $aircraft
 * @property-read \App\Models\Airline $airline
 * @property-read \App\Models\Airport $arrapt
 * @property-read \App\Models\User $captain
 * @property-read \App\Models\Airport $depapt
 * @property-read \App\Models\User $dispatcher
 * @property-read \App\Models\User $fo
 */
class LogbookEntry extends Model
{
    protected $fillable = [];

    public function captain()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function fo()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function dispatcher()
    {
        return $this->belongsTo('App\Models\User');
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
    public function alternates()
    {
        return $this->hasMany('App\Models\LogbookAlternate');
    }
    public function comment()
    {
        return $this->hasMany('App\Models\LogbookComment');
    }
}
