<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class AdminPerms
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
        // Check if the user is an admin. If not, deny him.
        if (!Auth::user()->admin)
            abort(403, 'Account does not have admin permissions');
        return $next($request);
    }
}
