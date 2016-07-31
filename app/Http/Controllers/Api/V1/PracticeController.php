<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PracticeController extends Controller
{
    /**
     * Get all available exams.
     *
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function exam()
    {
        $exams = Exam::where('enable', true)->latest()->get(['id', 'name']);

        $exams->each(function (Exam $exam) {
            $media = $exam->getFirstMedia('cover');

            $exam->setAttribute('cover', is_null($media) ? null : $media->getUrl('thumb'));
        });

        return $exams;
    }

    /**
     * Get random questions from specific exam.
     *
     * @param string $name
     *
     * @return Exam
     */
    public function processing($name)
    {
        return Exam::with([
            'questions' => function (HasMany $query) {
                $query->whereNull('question_id')->orderByRand()->limit(50);
            },
            'questions.options' => function (HasMany $query) {
                $query->orderByRand();
            },
            'questions.difficulty',
            'questions.questions',
            'questions.questions.options' => function (HasMany $query) {
                $query->orderByRand();
            },
            'questions.questions.difficulty',
        ])->where('enable', true)->where('name', $name)->firstOrFail();
    }
}
