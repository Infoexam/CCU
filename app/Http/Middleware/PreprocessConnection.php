<?php

namespace App\Http\Middleware;

use Agent;
use Carbon\Carbon;
use Closure;
use Cookie;
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
        $lan = 'en';

        if ($request->has('lang')) {
            $userAcceptLanguages = [strstr($request->input('lang'), '/', true)];
        } else if ($request->hasCookie('locale')) {
            $userAcceptLanguages = [$request->cookie('locale')];
        } else {
            $userAcceptLanguages = Agent::languages();
        }

        foreach (['zh-TW', 'zh-tw', 'zh'] as $zh) {
            if (in_array($zh, $userAcceptLanguages)) {
                $lan = 'zh-TW';
            }
        }

        app()->setLocale($lan);
        Carbon::setLocale($lan);

        /** @var $response \Illuminate\Http\Response */

        $response = $next($request);

        $response->withCookie(Cookie::forever('locale', $lan, null, null, false, false));

        return $response;
    }
}
