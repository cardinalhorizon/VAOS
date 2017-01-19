<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ACARSData extends Model
{
    protected $table = 'acarsdata';
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function bid()
    {
        return $this->belongsTo('App\Bid');
    }
}
