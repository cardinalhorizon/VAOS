<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LogbookComment
 *
 * @mixin \Eloquent
 * @property-read \App\Models\LogbookEntry $logbookentry
 * @property-read \App\Models\User $user
 */
class LogbookComment extends Model
{
    protected $fillable = [];

    public function logbookentry()
    {
        return $this->belongsTo('App\Models\LogbookEntry');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
