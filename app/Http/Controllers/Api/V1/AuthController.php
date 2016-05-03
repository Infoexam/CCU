<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Sign in to the application.
     *
     * @param Request $request
     *
     * @return \App\Accounts\User|void
     */
    public function signIn(Request $request)
    {
        if (! Auth::attempt($request->only(['username', 'password']))) {
            return $this->response->error('These credentials do not match our records.', 422);
        }

        return Auth::user();
    }

    /**
     * Sign out the application.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function signOut()
    {
        Auth::logout();

        return $this->response->noContent();
    }
}
