<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public $table = 'legacy_schedule';
    protected $guarded = [
        'id'
    ];
    public $timestamps = false;
}
