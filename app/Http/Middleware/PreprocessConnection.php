<?php

namespace App\Http\Middleware;

use Agent;
use Carbon\Carbon;
use Closure;
use Cookie;
use Hash;
use Illuminate\Http\Request;

class PreprocessConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // set the cost for BCRYPT to 12
        Hash::setRounds(12);

        // 設定環境語言
        $locale = $this->getLocale($request);

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        // 處理不支援的瀏覽器

        /** @var $response \Illuminate\Http\Response */

        $response = $next($request);

        $response->withCookie(Cookie::forever('locale', $locale, null, null, false, false));

        return $response;
    }

    /**
     * 取得環境語言
     *
     * @param Request $request
     * @param string $defaultLocale
     * @return string
     */
    protected function getLocale(Request $request, $defaultLocale = 'en')
    {
        $userAcceptLanguages = $this->getUserAcceptLanguages($request);

        foreach (['zh-TW', 'zh-tw', 'zh'] as $zh) {
            if (in_array($zh, $userAcceptLanguages)) {
                return 'zh-TW';
            }
        }

        return $defaultLocale;
    }

    /**
     * 取得使用者偏好語言
     *
     * @param Request $request
     * @return array
     */
    protected function getUserAcceptLanguages(Request $request)
    {
        if ($request->has('lang')) {
            return [strstr($request->input('lang'), '/', true)];
        } else if ($request->hasCookie('locale')) {
            return [$request->cookie('locale')];
        }

        return Agent::languages();
    }
}
