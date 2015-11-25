<?php

namespace App\Http\Middleware;

use Closure;

class AppendAdditionalHeaders
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
        $csp = "default-src 'none'; script-src 'self' 'unsafe-eval' https: cdn-infoexam.ccu.edu.tw cdnjs.cloudflare.com ajax.googleapis.com; style-src 'self' 'unsafe-inline' https: cdn-infoexam.ccu.edu.tw cdnjs.cloudflare.com fonts.googleapis.com; img-src 'self' https:; font-src https: cdnjs.cloudflare.com fonts.gstatic.com; connect-src 'self'";

        $headers = [
            'Content-Security-Policy' => $csp,
            'X-Content-Security-Policy' => $csp,
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'sameorigin',
            'X-XSS-Protection' => '1; mode=block',
        ];

        if ($request->secure() || env('WEBSITE_HTTPS', false)) {
            $headers['Strict-Transport-Security'] = 'max-age=15552000; preload';

            if (null !== ($pins = env('PUBLIC_KEY_PINS')) && strlen($pins) > 0) {
                $publicKeyPins = '';

                foreach (explode(',', $pins) as $pin) {
                    $publicKeyPins .= " pin-sha256=\"{$pin}\";";
                }

                $headers['Public-Key-Pins'] = "{$publicKeyPins} max-age=600;";
            }

            if (! $request->secure()) {
                return redirect()->secure($request->getRequestUri(), 302, $headers);
            }
        }

        /** @var $response \Illuminate\Http\Response */

        $response = $next($request);

        $response->headers->add($headers);

        return $response;
    }
}
