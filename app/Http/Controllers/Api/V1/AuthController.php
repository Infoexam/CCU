<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\User\Auth as Authenticate;
use App\Infoexam\User\User;
use Auth;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * 登入
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function signIn(Request $request)
    {
        if (! Auth::attempt($request->only(['username', 'password']))
            && ! Authenticate::attemptUsingCenter($request->input('username'), $request->input('password'))) {
            return response('', 422);
        }

        Authenticate::rehashPasswordIfNeeded(Auth::user(), $request->input('password'));

        return response()->json(['Intended' => session()->pull('url.intended')]);
    }

    /**
     * 登出
     *
     * @return \Symfony\Component\HttpFoundation\Response
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
        if (null !== config('infoexam.SSO_URL') && $request->has(['miXd', 'ticket'])) {
            $username = $this->ssoAuth($request->input('miXd'), $request->input('ticket'));

            if (false !== $username && null !== ($user = User::where('username', '=', $username)->first())) {
                Auth::loginUsingId($user->getAttribute('id'));
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
        $response = (new Client())->get(config('infoexam.SSO_URL'), ['query' => ['cid' => $miXd, 'ticket' => $ticket]]);

        $dom = new DOMDocument();

        if ($dom->loadXML(preg_replace('/<enter.*time>/', '', $response->getBody()->getContents()))
            && false !== ($data = simplexml_import_dom($dom))
            && ('Y' === (string) $data->sess_alive)) {
            return (string) $data->person_id;
        }

        return false;
    }
}
