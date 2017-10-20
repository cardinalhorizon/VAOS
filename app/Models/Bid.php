<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bid
 *
 * @mixin \Eloquent
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
    public function biddoc()
    {
        return $this->hasMany('App\Models\BidDoc');
    }

    public function bidcomment()
    {
        return $this->hasMany('App\Models\BidComment');
    }
}
