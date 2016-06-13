<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Exams\Option;
use App\Exams\Question;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ExamQuestionRequest;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamQuestionController extends Controller
{
    /**
     * Get the exam questions.
     *
     * @param string $name
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index($name)
    {
        return Exam::with([
            'category', 'questions' => function (HasMany $query) {
                $query->getBaseQuery()->select(['uuid', 'exam_id']);
            }, ])
            ->where('name', $name)
            ->firstOrFail(['id', 'name']);
    }

    /**
     * Create new exam question.
     *
     * @param ExamQuestionRequest $request
     * @param string $name
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(ExamQuestionRequest $request, $name)
    {
        $exam = Exam::where('name', $name)->firstOrFail(['id']);

        $question = $exam->questions()->save(new Question($request->input('question')));

        foreach ($request->input('option') as $option) {
            $question->options()->save(new Option($option));
        }

        return $this->response->created();
    }

    /**
     * Get exam question.
     *
     * @param string $name
     * @param string $uuid
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show($name, $uuid)
    {
        if (! Exam::where('name', $name)->exists()) {
            $this->response->errorNotFound();
        }

        $question = Question::with(['difficulty', 'options'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $question->makeVisible(['difficulty_id', 'question_id']);

        return $question;
    }

    /**
     * Update exam question.
     *
     * @param ExamQuestionRequest $request
     * @param string $name
     * @param string $uuid
     *
     * @return \Dingo\Api\Http\Response
     */
    public function update(ExamQuestionRequest $request, $name, $uuid)
    {
        if (! Exam::where('name', $name)->exists()) {
            $this->response->errorNotFound();
        }

        $question = Question::where('uuid', $uuid)->firstOrFail();

        $question->update($request->input('question'));

        foreach ($request->input('option') as $option) {
            Option::where('question_id', $question->getKey())
                ->findOrFail($option['id'] ?? 0)
                ->update($option);
        }

        return $this->response->noContent();
    }

    /**
     * Delete the question and it's related data.
     *
     * @param string $name
     * @param string $uuid
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($name, $uuid)
    {
        if (! Exam::where('name', $name)->exists()) {
            $this->response->errorNotFound();
        }

        $question = Question::where('uuid', $uuid)->firstOrFail();

        if (! $question->delete()) {
            $this->response->errorInternal();
        }

        return $this->response->noContent();
    }

    /**
     * Get the exam questions id and uuid.
     *
     * @param string $name
     *
     * @return \Dingo\Api\Http\Response
     */
    public function groups($name)
    {
        $exam = Exam::where('name', $name)->firstOrFail(['id']);

        return Question::where('exam_id', $exam->getKey())
            ->whereNull('question_id')
            ->latest()
            ->get(['id', 'uuid']);
    }
}
