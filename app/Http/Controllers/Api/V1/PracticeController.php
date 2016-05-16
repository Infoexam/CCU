<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PracticeController extends Controller
{
    /**
     * Get all available exams.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function exam()
    {
        return Exam::where('enable', true)->latest()->get(['id', 'name']);
    }

    /**
     * Get random questions from specific exam.
     *
     * @param string $name
     *
     * @return \Dingo\Api\Http\Response
     */
    public function processing($name)
    {
        return Exam::with([
            'questions' => function (HasMany $query) {
                $query->whereNull('question_id')->orderByRand()->limit(50);
            },
            'questions.options' => function (HasMany $query) {$query->orderByRand();},
            'questions.questions',
            'questions.questions.options' => function (HasMany $query) {$query->orderByRand();},
        ])->where('enable', true)->where('name', $name)->firstOrFail();
    }
}
