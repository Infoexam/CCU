<?php

namespace App\Http\Middleware;

use Closure;
use Hash;

class PreprocessConnection
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
        // set the cost for BCRYPT to 12
        Hash::setRounds(12);

        return $next($request);
    }
}
