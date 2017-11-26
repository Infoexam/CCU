<?php

namespace App\Http\Controllers;

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
            ->load(['applies', 'applies.result', 'applies.listing', 'applies.listing.applyType'])
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

    public function log(Request $request, $id)
    {
        $apply = Apply::with(['listing', 'result'])->where('id', $id);

        if ($request->user()->own('admin')) {
            $apply = $apply->first();
        } else {
            $apply = $apply->where('user_id', $request->user()->getKey())->first();
        }

        if (is_null($apply)) {
            $this->response->errorNotFound();
        }

        if (is_null($result = $apply->getRelation('result'))) {
            $this->response->errorNotFound();
        }

        $log = $result->getAttribute('log');

        if (is_string($log)) {
            $log = explode(PHP_EOL.PHP_EOL, $log);

            array_shift($log);

            foreach ($log as &$t) {
                if (str_contains($t, 'Word')) {
                    $t = '<h5>Word</h5>';
                } elseif (str_contains($t, 'Excel')) {
                    $t = '<h5>Excel</h5>';
                } elseif (str_contains($t, 'PowerPoint')) {
                    $t = '<h5>PowerPoint</h5>';
                } elseif (false !== ($pos = strpos($t, '原始配分'))) {
                    $t = substr($t, $pos).PHP_EOL;
                } else {
                    $t = $t.PHP_EOL;
                }
            }

            return implode('', $log);
        }

        $questions = $apply->getRelation('listing')->getAttribute('log')->getRelation('questions')->pluck('content', 'uuid');

        return ['questions' => $questions, 'result' => $log['result']];
    }
}
