<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $connection = 'phpVMS';
    protected $guarded = [
        'id'
    ];
    public $timestamps = false;
}
