<?php

namespace App\Http\Middleware;

use Agent;
use Carbon\Carbon;
use Closure;
use Cookie;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ParagonIE\CSPBuilder\CSPBuilder;

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
        $agent = $this->getBrowserAndVersion();

        // todo 可以增加設定來決定是否要啟用瀏覽器檢查
        if ($this->isUnsupportedBrowser($agent)) {
            // redirect to error page
        }

        /** @var $response \Illuminate\Http\Response */

        $response = $next($request);

        $response->withCookie(Cookie::forever('locale', $locale, null, null, false, false));

        $csp = CSPBuilder::fromFile(config_path('csp.json'));

        $csp->addDirective('upgrade-insecure-requests', $request->secure());

        $response->withHeaders($csp->getHeaderArray());

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

    /**
     * 取得使用者的瀏覽器以及版本
     *
     * @param string $agent
     * @return \Illuminate\Support\Collection
     */
    protected function getBrowserAndVersion($agent = '')
    {
        $agent = empty($agent) ? Agent::getUserAgent() : $agent;

        return collect([
            'browser' => Agent::browser($agent),
            'version' => Agent::version(Agent::browser($agent)),
        ]);
    }

    /**
     * 判斷是否為不支援的瀏覽器或版本
     *
     * @param Collection $agent
     * @return bool
     */
    protected function isUnsupportedBrowser(Collection $agent)
    {
        switch (true) {
            case ('IE' === $agent->get('browser')) && ($agent->get('version') < 11):
                return true;
        }

        return false;
    }
}
