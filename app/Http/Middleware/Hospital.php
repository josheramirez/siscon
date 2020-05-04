<?php

namespace siscont\Http\Middleware;

use Closure;

use Auth;

/**
 * Clase Middleware para Médico Hospital
 */
class Hospital
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
        if ( Auth::check() && Auth::user()->isRole('Hospital') )
        {
            return $next($request);
        }

        return redirect('home');
    }
}
