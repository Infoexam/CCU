<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infoexam\Eloquent\Models\Category;
use Infoexam\Eloquent\Models\Exam;

class PracticeController extends Controller
{
    /**
     * Get all available exams.
     *
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function exam()
    {
        $theories = Exam::has('questions')
            ->where('category_id', Category::getCategories('exam.category', 'theory'))
            ->where('enable', true)
            ->latest()
            ->get(['id', 'name']);

        $theories->each(function (Exam $exam) {
            $media = $exam->getFirstMedia('cover');

            $exam->setAttribute('cover', is_null($media) ? null : $media->getUrl('thumb'));
        });

        $techs = Exam::where('category_id', Category::getCategories('exam.category', 'tech'))
            ->where('enable', true)
            ->latest()
            ->get(['id', 'name']);

        $techs->each(function (Exam $exam) {
            $media = $exam->getFirstMedia('cover');

            $exam->setAttribute('cover', is_null($media) ? null : $media->getUrl('thumb'));

            $media = $exam->getFirstMedia('attachment');

            $exam->setAttribute('attachment', is_null($media) ? null : $media->getUrl());
        });

        return compact('theories', 'techs');
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
                $query->whereNull('question_id')->inRandomOrder()->limit(50);
            },
            'questions.options' => function (HasMany $query) {
                $query->inRandomOrder();
            },
            'questions.difficulty',
            'questions.questions',
            'questions.questions.options' => function (HasMany $query) {
                $query->inRandomOrder();
            },
            'questions.questions.difficulty',
        ])->where('enable', true)->where('name', $name)->firstOrFail();
    }
}
