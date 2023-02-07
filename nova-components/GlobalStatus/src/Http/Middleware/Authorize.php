<?php

namespace Wqa\GlobalStatus\Http\Middleware;

use Wqa\GlobalStatus\GlobalStatus;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(GlobalStatus::class)->authorize($request) ? $next($request) : abort(403);
    }
}
