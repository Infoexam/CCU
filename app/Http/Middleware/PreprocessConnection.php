<?php

namespace App\Http\Middleware;

use Agent;
use App;
use Carbon\Carbon;
use Closure;
use Config;
use Hash;
use ParagonIE\CSPBuilder\CSPBuilder;

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

        // Set the password work factor
        Hash::setRounds(12);

        // Set session to secure if the request is secure
        Config::set('session.secure', $this->request->secure());

        // Set the application locale
        $this->setLocale();

        $this->response = $next($this->request);

        // Append the csp header to http response
        $this->appendCsp();

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

    /**
     * Append the csp header to http response.
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function appendCsp()
    {
        $csp = CSPBuilder::fromFile(config_path('csp.json'));

        $csp->addDirective('upgrade-insecure-requests', $this->request->secure());

        $this->response->withHeaders($csp->getHeaderArray());
    }
}
