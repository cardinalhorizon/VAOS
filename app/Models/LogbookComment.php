<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogbookComment extends Model
{
    public $table = 'pirep_comments';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function pirep()
    {
        return $this->belongsTo('App\PIREP');
    }
}
