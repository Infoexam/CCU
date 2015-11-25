<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['only' => 'admin']);

        $this->middleware('auth', ['only' => 'exam']);
    }

    /**
     * 網站首頁
     *
     * @return \Illuminate\View\View
     */
    public function student()
    {
        return view('home');
    }

    /**
     * 管理頁面
     *
     * @return \Illuminate\View\View
     */
    public function admin()
    {
        return view('admin.home');
    }

    /**
     * 考試頁面
     *
     * @return \Illuminate\View\View
     */
    public function exam()
    {
        //
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

        if (! hash_equals($hash, hash_hmac($algo, $request->getContent(), env('GITHUB_WEBHOOK_SECRET')))) {
            \Log::notice('Github Webhook', ['auth' => 'failed', 'ip' => $request->ip()]);
        } else {
            \Log::info('Github Webhook', ['auth' => 'success', 'ip' => $request->ip()]);

            \Artisan::queue('deploy');
        }

        return $this->ok();
    }
}
