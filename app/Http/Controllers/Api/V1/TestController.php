<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Infoexam\Eloquent\Models\Apply;
use Infoexam\Eloquent\Models\Listing;
use Infoexam\Eloquent\Models\Result;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use SnappyPdf;

class TestController extends Controller
{
    protected $now;

    public function __construct()
    {
        $this->now = Carbon::now();
    }

    public function store(Request $request, $code)
    {
        $listing = Listing::with([
            'applies' => function ($query) use ($request) {
                $query->where('user_id', $request->user()->getkey());
            },
            'applies.result',
        ])
            ->where('started_at', '<=', $this->now)
            ->where('ended_at', '>=', $this->now)
            ->whereHas('applies', function ($query) use ($request) {
                $query->where('user_id', $request->user()->getKey());
            })
            ->first();

        if (is_null($listing) || $code !== $listing->getAttribute('code')) {
            $this->response->errorNotFound();
        }

        $apply = $listing->getRelation('applies')->first();
        $result = $apply->getRelation('result');

        $result->update([
            'submitted_at' => $this->now,
            'log' => $request->all(),
        ]);

        return $this->response->created();
    }

    public function index(Request $request)
    {
        if (! $request->user()->own('admin')) {
            return Listing::with(['applyType', 'subject'])
                ->where('began_at', '<=', $this->now)
                ->where('ended_at', '>=', $this->now)
                ->whereHas('applies', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->getKey());
                })
                ->get();
        }

        return Listing::with(['applyType', 'subject'])
            ->where('ended_at', '>=', $this->now)
            ->orderBy('began_at')
            ->get();
    }

    public function show(Request $request, $code)
    {
        $listing = Listing::with([
            'applies' => function ($query) use ($request) {
                $query->where('user_id', $request->user()->getkey());
            },
            'applies.result',
        ])
            ->where('started_at', '<=', $this->now)
            ->where('ended_at', '>=', $this->now)
            ->whereHas('applies', function ($query) use ($request) {
                $query->where('user_id', $request->user()->getKey());
            })
            ->first();

        if (is_null($listing) || $code !== $listing->getAttribute('code')) {
            $this->response->errorNotFound();
        }

        $apply = $listing->getRelation('applies')->first();
        $result = $apply->getRelation('result');

        if (! is_null($result)) {
            if (! $result->getAttribute('re_sign_in')) {
                $this->response->error('Conflict', 409);
            } else {
                $result->update([
                    're_sign_in' => false,
                    'signed_in_at' => $this->now,
                ]);
            }
        } else {
            $apply->result()->save(new Result([
                'duration' => $listing->getAttribute('duration'),
                'signed_in_at' => $this->now,
            ]));

            $listing->increment('tested_num');
        }

        return $listing->getAttribute('log')->getRelation('questions');
    }

    public function timing($code)
    {
        $listing = Listing::where('code', $code)->first();

        if (is_null($listing)) {
            return 0;
        }

        $timing = $this->now->diffInSeconds($listing->getAttribute('ended_at'));

        return max($timing - 3, 0);
    }

    public function manage($code)
    {
        $listing = Listing::with(['applyType', 'subject', 'applies', 'applies.result', 'applies.user'])
            ->where('code', $code)
            ->firstOrFail();

        return $listing;
    }

    public function checkIn($code)
    {
        $listing = Listing::with(['applies', 'applies.user', 'applyType', 'subject'])->where('code', $code)->firstOrFail();

        $pdf = SnappyPdf::loadView('vendor.pdfs.check-in', compact('listing'));

        return $pdf->inline($listing->getAttribute('code').'.pdf');
    }

    public function pc2($code)
    {
        $listing = Listing::with(['applies', 'applies.user', 'subject'])->where('code', $code)->firstOrFail();

        return Excel::create($listing->getAttribute('code'), function (LaravelExcelWriter $excel) use ($listing) {
            $excel->sheet('testlist', function (LaravelExcelWorksheet $sheet) use ($listing) {
                $sheet->appendRow(['學號', '姓名', '測驗代碼', '測驗類別', '測驗日期', '測驗地點', '開始時間', '結束時間']);

                foreach ($listing->getRelation('applies') as $apply) {
                    $sheet->appendRow([
                        $apply->getRelation('user')->getAttribute('username'),
                        $apply->getRelation('user')->getAttribute('name'),
                        $listing->getAttribute('code'),
                        explode(' ', trans('infoexam.exam.subject.'.$listing->getRelation('subject')->getAttribute('name')), 2)[1],
                        $listing->getAttribute('began_at')->toDateString(),
                        $listing->getAttribute('room'),
                        $listing->getAttribute('began_at')->toTimeString(),
                        $listing->getAttribute('ended_at')->toTimeString(),
                    ]);
                }
            });
        })->download('xlsx');
    }

    public function start($code)
    {
        $listing = Listing::with(['paper', 'paper.questions', 'paper.questions.options'])
            ->where('code', $code)
            ->firstOrFail();

        if (! is_null($listing->getAttribute('started_at'))) {
            $this->response->errorMethodNotAllowed();
        } elseif ($this->now->diffInSeconds($listing->getAttribute('began_at'), false) > 0) {
            $this->response->errorBadRequest();
        }

        $listing->update([
            'started_at' => $this->now,
            'log' => $listing->getRelation('paper'),
        ]);

        return $this->response->noContent();
    }

    public function extend(Request $request, $code)
    {
        $listing = Listing::where('code', $code)->firstOrFail();

        $listing->update([
            'duration' => $listing->getAttribute('duration') + intval($request->input('minutes', 0)),
        ]);

        return $this->response->noContent();
    }

    public function redo(Request $request, $code)
    {
        $listing = Listing::where('code', $code)->firstOrFail();

        $apply = Apply::with(['result'])->findOrFail($request->input('id', 0));

        if ($apply->getAttribute('listing_id') !== $listing->getKey()) {
            $this->response->errorBadRequest();
        } elseif (is_null($apply->getRelation('result'))) {
            $this->response->errorBadRequest();
        }

        $apply->getRelation('result')->update(['re_sign_in' => true]);

        return $this->response->noContent();
    }
}
