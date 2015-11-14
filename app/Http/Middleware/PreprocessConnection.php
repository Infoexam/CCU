<?php

namespace App\Http\Middleware;

use Agent;
use Carbon\Carbon;
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

        // set environment and carbon default language
        $userAcceptLanguages = Agent::languages();
        $lan = 'en';

        foreach (['zh-TW', 'zh-tw', 'zh'] as $zh) {
            if (in_array($zh, $userAcceptLanguages)) {
                $lan = 'zh-TW';
            }
        }

        app()->setLocale($lan);
        Carbon::setLocale($lan);

        return $next($request);
    }
}
