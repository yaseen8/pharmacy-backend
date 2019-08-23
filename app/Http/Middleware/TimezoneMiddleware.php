<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

class TimezoneMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('timezone')) {

            config(['app.timezone' => $request->header('timezone')]);
        }
        $response = $next($request);

        return $response;
    }

}