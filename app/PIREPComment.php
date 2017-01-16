<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PIREPComment extends Model
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
