<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ActiveAccountCheck
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
        if (Auth::user()->status == 0) {
            $request->session()->flash('AppInProgress', true);

            return redirect('/status');
        } elseif (Auth::user()->status == 2) {
            $request->session()->flash('AccountDisabled', true);

            return redirect('/status');
        }

        return $next($request);
    }
}
