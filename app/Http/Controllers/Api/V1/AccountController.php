<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Get user profile.
     *
     * @param Request $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function profile(Request $request)
    {
        if (is_null($request->user())) {
            $this->response->errorUnauthorized();
        }

        return $request->user();
    }
}
