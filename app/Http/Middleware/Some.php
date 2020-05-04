<?php

namespace siscont\Http\Middleware;

use Closure;

use Auth;

/**
 * Clase Middleware para SOME APS
 */
class Some
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
        if ( Auth::check() && Auth::user()->isRole('Some') )
        {
            return $next($request);
        }

        return redirect('home');
    }
}
