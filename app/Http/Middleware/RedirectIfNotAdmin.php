<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::user()) {
            return redirect()->route('login');
        } elseif (! Auth::user()->is_admin) {
            return response("You don't have the required privileges to access this area.", \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
