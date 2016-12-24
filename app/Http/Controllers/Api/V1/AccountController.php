<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Hashids;
use Illuminate\Http\Request;
use Infoexam\Eloquent\Models\Apply;

class AccountController extends Controller
{
    /**
     * Get user profile.
     *
     * @param Request $request
     *
     * @return \Infoexam\Eloquent\Models\User
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

    public function apply(Request $request)
    {
        if (! $request->has(['token', 'checksum'])) {
            $this->response->errorNotFound();
        }

        $ids = Hashids::connection()->decode($request->input('checksum'));

        if (empty($ids)) {
            $this->response->errorNotFound();
        }

        $apply = Apply::with(['user', 'listing', 'listing.subject'])
            ->where('user_id', $ids[0])
            ->where('listing_id', $ids[1])
            ->where('token', $request->input('token'))
            ->first();

        if (is_null($apply)) {
            $this->response->errorNotFound();
        } elseif (Carbon::now()->diffInMinutes($apply->getRelation('listing')->getAttribute('ended_at')) < 0) {
            $this->response->errorNotFound();
        }

        return $apply;
    }
}
