<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class APIKeyCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $dbKey = DB::table('api_keys')->where('key', $request->query('key'))->first();
        if (! empty($dbKey)) {
            return $next($request);
        } else {
            return response('Invalid Key', 403);
        }
    }
}
