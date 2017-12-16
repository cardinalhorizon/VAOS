<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ActiveAccountCheck
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
        if (Auth::user()->status == 0)
        {
            $request->session()->flash('AppInProgress', true);
            Auth::logout();
            return redirect('/login');
        }
        else if (Auth::user()->status == 2)
        {
            $request->session()->flash('AccountDisabled', true);
            Auth::logout();
            return redirect('/login');
        }
        return $next($request);
    }
}
