<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class APIKey extends Model
{
    //
    /**
     * @return $this
     */
    public function routes()
    {
        return $this->belongsToMany('App\APIRoute', 'api_perms','apikey', 'apiroute');
    }
}
