<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bid.
 *
 * @mixin \Eloquent
 *
 * @property-read \App\Models\Aircraft $aircraft
 * @property-read \App\Models\Airline $airline
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BidAlternate[] $alternates
 * @property-read \App\Models\Airport $arrapt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BidComment[] $bidcomment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BidDoc[] $biddoc
 * @property-read \App\Models\User $captain
 * @property-read \App\Models\Airport $depapt
 * @property-read \App\Models\User $dispatcher
 * @property-read \App\Models\User $fo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BidComment[] $comment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BidDoc[] $doc
 * @property-read \App\Models\TelemetryPoint $telemetrypoint
 */
class Bid extends Model
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
        return $this->hasMany('App\Models\BidAlternate');
    }

    public function doc()
    {
        return $this->hasMany('App\Models\BidDoc');
    }

    public function comment()
    {
        return $this->hasMany('App\Models\BidComment');
    }

    public function telemetrypoint()
    {
        return $this->hasOne('App\Models\TelemetryPoint');
    }
}
