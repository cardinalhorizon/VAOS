<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class APIRoute extends Model
{
    protected $table = 'api_routes';
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function keys()
    {
        return $this->belongsToMany('App\APIKey', 'api_perms', 'apiroute', 'apikey')->withPivot('enabled');
    }
}
