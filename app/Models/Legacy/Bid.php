<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $guarded = [];
    protected $connection = 'phpVMS';
    public $timestamps = false;
}
