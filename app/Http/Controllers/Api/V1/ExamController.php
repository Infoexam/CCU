<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ExamRequest;

class ExamController extends Controller
{
    /**
     * Get the exam list.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $exams = Exam::with(['category'])->latest()->paginate(5);

        foreach ($exams->items() as $exam) {
            $media = $exam->getFirstMedia('cover');

            $exam->setAttribute('cover', is_null($media) ? null : $media->getUrl('thumb'));
        }

        return $exams;
    }

    /**
     * Create a new exam.
     *
     * @param ExamRequest $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(ExamRequest $request)
    {
        $exam = Exam::create($request->only(['category_id', 'name', 'enable']));

        if (! $exam->exists) {
            $this->response->errorInternal();
        }

        $exam->uploadImages($request->file('cover'), 'cover');

        return $this->response->created(null, $exam);
    }

    /**
     * Get the exam data.
     *
     * @param int $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $exam = Exam::findOrFail($id, ['id', 'category_id', 'name', 'enable']);

        $exam->makeVisible(['category_id']);

        return $exam;
    }

    /**
     * Update the exam data.
     *
     * @param ExamRequest $request
     * @param int $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function update(ExamRequest $request, $id)
    {
        $exam = Exam::findOrFail($id);

        if (! $exam->update($request->only(['category_id', 'name', 'enable']))) {
            $this->response->errorInternal();
        }

        if ($request->hasFile('cover')) {
            $exam->clearMediaCollection('cover');

            $exam->uploadImages($request->file('cover'), 'cover');
        }

        return $exam;
    }

    /**
     * Delete the exam and it's related data.
     *
     * @param int $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        if (! Exam::findOrFail($id)->delete()) {
            $this->response->errorInternal();
        }

        return $this->response->noContent();
    }
}
