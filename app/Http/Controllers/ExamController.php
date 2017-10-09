<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamRequest;
use Illuminate\Http\Request;
use Infoexam\Eloquent\Models\Category;
use Infoexam\Eloquent\Models\Exam;

class ExamController extends Controller
{
    /**
     * Get the exam list.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        if ($request->has('listing')) {
            $theoryId = Category::getCategories('exam.category', 'theory');

            return Exam::where('category_id', $theoryId)
                ->where('enable', true)
                ->get(['id', 'name']);
        }

        $exams = Exam::with(['category'])->latest()->paginate(5);

        foreach ($exams->items() as $exam) {
            $media = $exam->getFirstMedia('cover');

            $exam->setAttribute('cover', is_null($media) ? null : $media->getUrl('thumb'));

            if ('tech' === $exam->getRelation('category')->getAttribute('name')) {
                $media = $exam->getFirstMedia('attachment');

                $exam->setAttribute('attachment', is_null($media) ? null : $media->getUrl());
            }
        }

        return $exams;
    }

    /**
     * Create a new exam.
     *
     * @param ExamRequest $request
     *
     * @return Exam
     */
    public function store(ExamRequest $request)
    {
        $exam = Exam::create($request->only(['category_id', 'name', 'enable']));

        if (! $exam->exists) {
            $this->response->errorInternal();
        }

        $exam->uploadMedias($request->file('cover'), 'cover');

        if ($request->hasFile('attachment')) {
            $exam->uploadMedias($request->file('attachment'), 'attachment');
        }

        return $exam;
    }

    /**
     * Get the exam data.
     *
     * @param string $name
     *
     * @return Exam
     */
    public function show($name)
    {
        $exam = Exam::where('name', $name)->firstOrFail(['id', 'category_id', 'name', 'enable']);

        $exam->makeVisible(['category_id']);

        return $exam;
    }

    /**
     * Update the exam data.
     *
     * @param ExamRequest $request
     * @param string $name
     *
     * @return Exam
     */
    public function update(ExamRequest $request, $name)
    {
        $exam = Exam::where('name', $name)->firstOrFail();

        if (! $exam->update($request->only(['category_id', 'name', 'enable']))) {
            $this->response->errorInternal();
        }

        if ($request->hasFile('cover')) {
            $exam->clearMediaCollection('cover');

            $exam->uploadMedias($request->file('cover'), 'cover');
        }

        if ($request->hasFile('attachment')) {
            $exam->clearMediaCollection('attachment');

            $exam->uploadMedias($request->file('attachment'), 'attachment');
        }

        return $exam;
    }

    /**
     * Delete the exam and it's related data.
     *
     * @param string $name
     *
     * @return \Dingo\Api\Http\Response
     *
     * @todo 檢查題目是否已用於試卷
     */
    public function destroy($name)
    {
        if (! Exam::where('name', $name)->firstOrFail(['id', 'name'])->delete()) {
            $this->response->errorInternal();
        }

        return $this->response->noContent();
    }
}
