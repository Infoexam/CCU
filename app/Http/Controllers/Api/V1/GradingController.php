<?php

namespace App\Http\Controllers\Api\V1;

use Alchemy\Zippy\Zippy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GradingImportRequest;
use App\Http\Requests\Api\V1\GradingRequest;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Infoexam\Eloquent\Models\Apply;
use Infoexam\Eloquent\Models\Listing;

class GradingController extends Controller
{
    /**
     * Get listing list.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        return Listing::with(['applyType', 'subject'])
            ->where('ended_at', '<=', Carbon::now())
            ->latest('ended_at')
            ->orderBy('room')
            ->paginate(10);
    }

    public function show($code)
    {
        $listing = Listing::with(['applies', 'applies.user', 'applies.result'])->where('code', $code)->firstOrFail();

        $applies = $listing->getRelation('applies');

        return $applies;
    }

    public function update(GradingRequest $request, $code)
    {
        $listing = Listing::where('code', $code)->firstOrFail(['id']);

        $apply = Apply::with(['result'])->where('listing_id', $listing->getKey())->findOrFail($request->input('id'));

        if (! $apply->getRelation('result')->update($request->only(['score']))) {
            $this->response->errorInternal();
        }

        return $this->response->noContent();
    }

    public function import(GradingImportRequest $request, Filesystem $fs, $code)
    {
        $listing = Listing::with(['applies', 'applies.user', 'applies.result'])->where('code', $code)->firstOrFail();

        $applies = $listing->getRelation('applies');

        $path = storage_path("scores/{$code}");

        if (! $fs->exists($path)) {
            $fs->makeDirectory($path);
        }

        $file = $request->file('file')->move(storage_path('temp'), str_random(4).'-'.$request->file('file')->getClientOriginalName());

        Zippy::load()->open($file->getRealPath())->extract($path);

        $fs->delete($file->getRealPath());

        foreach ($fs->files($path) as $target) {
            $logs = explode(PHP_EOL, trim($fs->get($target)));

            $username = null;
            $score = 0;

            foreach ($logs as &$log) {
                $log = trim($log);

                if (preg_match('/^\d{9}$/', $log)) {
                    $username = $log;
                } elseif (preg_match('/^.+總分為(\d+)$/u', $log, $matches)) {
                    $score += intval($matches[1]);
                }
            }

            $user = \Infoexam\Eloquent\Models\User::where('username', $username)->first(['id']);

            if (is_null($user)) {
                // 系統無該使用者
            } else {
                $index = $applies->search(function ($apply) use ($user) {
                    return $user->getKey() === $apply->getAttribute('user_id');
                });

                if (false === $index) {
                    // 有作答記錄但無報名資料
                } else {
                    $result = $applies[$index]->getRelation('result');

                    if (is_null($result)) {
                        // 未到考
                    } else {
                        $result->update([
                            'score' => $score,
                            'log' => implode(PHP_EOL, $logs),
                        ]);
                    }
                }
            }
        }
    }
}
