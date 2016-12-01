<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Listing;
use App\Exams\Result;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $now;

    public function __construct()
    {
        $this->now = Carbon::now();
    }

    public function store(Request $request)
    {
        //
    }

    public function index(Request $request)
    {
        if (! $request->user()->own('admin')) {
            return Listing::where('began_at', '<=', $this->now)
                ->where('ended_at', '>=', $this->now)
                ->whereHas('applies', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->getKey());
                })
                ->get();
        }

        return Listing::where('ended_at', '>=', $this->now)->orderBy('began_at')->get();
    }

    public function show(Request $request, $code)
    {
        $listing = Listing::with([
            'paper',
            'paper.questions' => function ($query) {
                $query->select(['questions.id', 'uuid', 'questions.content', 'multiple']);
            },
            'paper.questions.options' => function ($query) {
                $query->select(['options.id', 'question_id', 'options.content']);
            },
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
                // @todo Dev
//                $this->response->error('Conflict', 409);
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
        }

        return $listing->getRelation('paper')->getRelation('questions');
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
}
