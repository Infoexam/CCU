<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Get user profile.
     *
     * @param Request $request
     *
     * @return \App\Accounts\User
     */
    public function profile(Request $request)
    {
        return $request->user();
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function applies(Request $request)
    {
        return $request->user()
            ->load(['applies', 'applies.result', 'applies.listing'])
            ->getRelation('applies');
    }
}
