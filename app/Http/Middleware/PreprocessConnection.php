<?php

namespace App\Http\Middleware;

use Agent;
use App;
use Carbon\Carbon;
use Closure;
use Config;

class PreprocessConnection
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Http\Response
     */
    protected $response;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;

        // Set session to secure if the request is secure
        Config::set('session.secure', $this->request->secure());

        // Set the application locale
        $this->setLocale();

        $this->response = $next($this->request);

        return $this->response;
    }

    /**
     * Set the application locale.
     *
     * @return void
     */
    protected function setLocale()
    {
        $locale = $this->getLocale();

        App::setLocale($locale);

        Carbon::setLocale($locale);
    }

    /**
     * Get the user accept language.
     *
     * @param string $defaultLocale
     *
     * @return string
     */
    protected function getLocale($defaultLocale = 'en')
    {
        $userAcceptLanguages = $this->getUserAcceptLanguages();

        foreach (['zh-TW', 'zh-tw', 'zh'] as $zh) {
            if (in_array($zh, $userAcceptLanguages)) {
                return 'zh-TW';
            }
        }

        return $defaultLocale;
    }

    /**
     * Get the user prefer languages.
     *
     * @return array
     */
    protected function getUserAcceptLanguages()
    {
        if ($this->request->has('lang')) {
            return [strstr($this->request->input('lang'), '/', true)];
        } elseif ($this->request->hasCookie('locale')) {
            return [$this->request->cookie('locale')];
        }

        return Agent::languages();
    }
}
