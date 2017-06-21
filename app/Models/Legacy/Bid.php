<?php

namespace App\Models\Legacy;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $guarded = [];
    public $table = 'legacy_create';
    public $timestamps = false;
}
