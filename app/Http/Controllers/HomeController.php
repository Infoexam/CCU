<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Artisan;
use Illuminate\Http\Request;
use Log;

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
     * Auto update when github pushed.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deploy(Request $request)
    {
        list($algo, $hash) = explode('=', $request->header('X-Hub-Signature', 'sha1=failed'), 2);

        if (hash_equals($hash, hash_hmac($algo, $request->getContent(), config('infoexam.github_webhook_secret')))) {
            Artisan::queue('deploy');

            $success = 'success';
        }

        Log::info('Github-Webhook', [
            'auth' => $success ?? 'failed',
            'ip' => $request->ip(),
        ]);

        return $this->response->noContent();
    }
}
