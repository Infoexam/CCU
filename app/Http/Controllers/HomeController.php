<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Get home page view.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        return view('home');
    }

    /**
     * 自動化佈署
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deploy(Request $request)
    {
        list($algo, $hash) = explode('=', $request->header('X-Hub-Signature'), 2);

        if (! hash_equals($hash, hash_hmac($algo, $request->getContent(), config('infoexam.github_webhook_secret')))) {
            \Log::notice('Github Webhook', ['auth' => 'failed', 'ip' => $request->ip()]);
        } else {
            \Log::info('Github Webhook', ['auth' => 'success', 'ip' => $request->ip()]);

            \Artisan::queue('deploy');
        }

        return response()->json('', 200);
    }

    /**
     * 清除 opcache
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function opcacheReset()
    {
        return response()->json(opcache_reset(), 200);
    }
}
