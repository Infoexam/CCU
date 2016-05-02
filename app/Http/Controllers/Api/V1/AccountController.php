<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function profile(Request $request)
    {
        if (is_null($request->user())) {
            return $this->response->errorUnauthorized();
        }

        return $request->user();
    }
}
