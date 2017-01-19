<?php

namespace App\Http\Middleware;

use App\ACARS_Session;
use Closure;

class APIAuth1_0
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check to see if we are handling Session Based Authentication
        if ($request->auth = 'session')
        {
            $res = ACARS_Session::where('userid', $request->userid)->first();

            // They failed session authentication.
            if (!$res)
            {
                return json_encode(['status' => 503]);
            }
        }
        return $next($request);
    }
}
