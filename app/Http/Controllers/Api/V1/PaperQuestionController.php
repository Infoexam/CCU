<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Exams\Paper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PaperQuestionRequest;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaperQuestionController extends Controller
{
    /**
     * Get the paper questions.
     *
     * @param $name
     *
     * @return Paper
     */
    public function index($name)
    {
        return Paper::with(['questions' => function (BelongsToMany $query) {
            $query->getBaseQuery()->select(['uuid', 'exam_id']);
        }, 'questions.exam' => function (BelongsTo $query) {
            $query->getBaseQuery()->select(['id', 'name']);
        }])
            ->where('name', $name)
            ->firstOrFail(['id', 'name']);
    }

    /**
     * Update the paper questions.
     *
     * @param PaperQuestionRequest $request
     * @param string $name
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(PaperQuestionRequest $request, $name)
    {
        Paper::where('name', $name)
            ->firstOrFail(['id'])
            ->questions()
            ->sync($request->input('question'));

        return $this->response->created();
    }

    /**
     * Get the paper questions' id.
     *
     * @param string $name
     *
     * @return \Illuminate\Support\Collection
     */
    public function show($name)
    {
        return Paper::with(['questions' => function (BelongsToMany $query) {
            $query->getBaseQuery()->select(['questions.id']);
        }])
            ->where('name', $name)
            ->firstOrFail(['id'])
            ->getRelation('questions')
            ->pluck('id');
    }

    /**
     * Get all enable exams' questions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Exam::with(['questions' => function (HasMany $query) {
            $query->getBaseQuery()->select(['id', 'uuid', 'exam_id']);
        }])
            ->where('enable', true)
            ->get(['id', 'name']);
    }
}
