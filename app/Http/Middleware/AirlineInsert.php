<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Airline;

class AirlineInsert
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
        $params = $request->route()->parameters();

        try {
            $airline = Airline::where('url_slug', $params['airline'])->firstOrFail();
        } catch (Exception $e) {
            return response()->view('error.404');
        }
        $request->attributes->add(['airline' => $airline]);

        return $next($request);
    }
}
