<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\User\Auth as Authenticate;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * 登入
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function signIn(Request $request)
    {
        if (! Auth::attempt($request->only(['username', 'password']))
            && ! Authenticate::attemptUsingCenter($request->input('username'), $request->input('password'))) {
            return response()->json(['errors' => ['auth' => trans('auth.failed')]], 422);
        }

        Authenticate::rehashPasswordIfNeeded(Auth::user(), $request->input('password'));

        return $this->ok();
    }

    /**
     * 登出
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function signOut()
    {
        Auth::logout();

        return redirect()->route('home');
    }

    /**
     * 單一入口登入
     */
    public function sso()
    {
        //
    }
}
