<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaperQuestionRequest;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infoexam\Eloquent\Models\Category;
use Infoexam\Eloquent\Models\Exam;
use Infoexam\Eloquent\Models\Paper;

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
            ->sync($request->input('question', []));

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
        $theoryId = Category::getCategories('exam.category', 'theory');

        return Exam::with(['questions' => function (HasMany $query) {
            $query->getBaseQuery()->select(['id', 'uuid', 'exam_id']);
        }])
            ->where('category_id', $theoryId)
            ->where('enable', true)
            ->get(['id', 'name']);
    }
}
