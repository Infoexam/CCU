<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Artisan;
use Auth;
use DOMDocument;
use GuzzleHttp\Client;
use Hash;
use Illuminate\Http\Request;
use Infoexam\Eloquent\Models\User;
use Redirect;
use Session;

class AuthController extends Controller
{
    /**
     * Sign in to Infoexam.
     *
     * @param Request $request
     *
     * @return \Infoexam\Eloquent\Models\User
     */
    public function signIn(Request $request)
    {
        $credentials = $request->only(['username', 'password']);

        if (Auth::attempt($credentials)) {
            return Auth::user();
        } elseif (User::where('username', $credentials['username'])->exists()) {
            Artisan::call('sync:password', ['--id' => [$credentials['username']]]);

            if (Auth::attempt($credentials)) {
                return Auth::user();
            }
        }

        return $this->response->error('auth.failed', 422);
    }

    /**
     * Sign out of Infoexam.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function signOut()
    {
        Session::clear();

        Auth::logout();

        return $this->response->noContent();
    }

    /**
     * Sign in from old website.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function oldWebsite(Request $request)
    {
        if ($request->has(['token'])) {
            $token = base64_decode($request->input('token'), true);

            if (false !== $token && Hash::check(config('infoexam.token'), $token)) {
                $user = User::where('username', 'guest')->first();

                if (! is_null($user)) {
                    Auth::login($user);
                }
            }
        }

        return Redirect::to('/?oauth');
    }

    /**
     * Sign in using sso.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sso(Request $request)
    {
        if (! is_null(config('infoexam.sso_url')) && $request->has(['miXd', 'ticket'])) {
            $username = $this->ssoAuth($request->input('miXd'), $request->input('ticket'));

            if (false !== $username && ! is_null($user = User::where('username', $username)->first())) {
                Auth::guard()->login($user, true);
            }
        }

        return Redirect::to('/?oauth');
    }

    /**
     * SSO Auth.
     *
     * @param string $miXd
     * @param string $ticket
     *
     * @return bool|string
     */
    protected function ssoAuth($miXd, $ticket)
    {
        $response = (new Client())->get(config('infoexam.sso_url'), [
            'http_errors' => false,
            'query'       => ['cid' => $miXd, 'ticket' => $ticket],
        ]);

        if (200 !== $response->getStatusCode()) {
            return false;
        }

        $dom = new DOMDocument();

        if ($dom->loadXML(preg_replace('/<enter.*time>/', '', $response->getBody()->getContents()))
            && false !== ($data = simplexml_import_dom($dom))
            && ('Y' === (string) $data->sess_alive)) {
            return (string) $data->person_id;
        }

        return false;
    }
}
