<?php

namespace App\Http\Controllers\Api\V1;

use App\Infoexam\User\User;
use Auth;
use DB;
use DOMDocument;
use GuzzleHttp\Client;
use Hash;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    /**
     * 登入
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(Request $request)
    {
        if (! Auth::attempt($request->only(['username', 'password']), true)
            && ! $this->attemptCenter($request->input('username'), $request->input('password'))) {
            return $this->setMessages(['Invalid username or password.'])->responseUnprocessableEntity();
        }

        $this->refreshPassword(Auth::user(), $request->input('password'));


        return $this->setHeaders(['Intended' => $this->getIntended()])->responseOk();
    }

    /**
     * Get intended url
     *
     * @return string
     */
    protected function getIntended()
    {
        if (Auth::user()->hasRole(['admin'])) {
            return route('home.admin');
        }

        return session()->pull('url.intended');
    }

    /**
     * 嘗試從中心伺服器登入
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    protected function attemptCenter($username, $password)
    {
        if (! app()->environment(['production', 'development'])) {
            return false;
        }

        /*
         * 1. 確認本地帳號存在
         * 2. 中心驗證帳號
         */
        if (is_null($user = User::where('username', $username)->first(['id', 'password']))) {
            return false;
        } else if (is_null($new = DB::connection('elearn')->table('std_info')
                ->where('std_no', $username)->where('user_pass', $password)->first(['user_pass']))) {
            return false;
        }

        // 登入並更新本地密碼
        Auth::loginUsingId($user->getAttribute('id'), true)->update(['password' => bcrypt($new->user_pass)]);

        return true;
    }

    /**
     * 如果需要，則重新 hash 密碼
     *
     * @param User $user
     * @param string $password
     */
    protected function refreshPassword(User $user, $password)
    {
        if (Hash::needsRehash($user->getAttribute('password'))) {
            $user->update([
                'password' => bcrypt($password),
            ]);
        }
    }

    /**
     * 登出
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signOut()
    {
        Auth::logout();

        return redirect()->home();
    }

    /**
     * 單一入口登入
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sso(Request $request)
    {
        if (! is_null(config('infoexam.sso_url')) && $request->has(['miXd', 'ticket'])) {
            $username = $this->ssoAuth($request->input('miXd'), $request->input('ticket'));

            if (false !== $username && ! is_null($user = User::where('username', $username)->first())) {
                Auth::loginUsingId($user->getAttribute('id'), true);
            }
        }

        return redirect()->home();
    }

    /**
     * SSO Auth
     *
     * @param string $miXd
     * @param string $ticket
     * @return bool|string
     */
    protected function ssoAuth($miXd, $ticket)
    {
        $response = (new Client())->get(config('infoexam.sso_url'), ['query' => ['cid' => $miXd, 'ticket' => $ticket]]);

        $dom = new DOMDocument();

        if ($dom->loadXML(preg_replace('/<enter.*time>/', '', $response->getBody()->getContents()))
            && false !== ($data = simplexml_import_dom($dom))
            && ('Y' === (string) $data->sess_alive)) {
            return (string) $data->person_id;
        }

        return false;
    }
}
