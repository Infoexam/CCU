<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Exams\Option;
use App\Exams\Question;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\QuestionRequest;
use Illuminate\Http\Request;

class ExamQuestionController extends Controller
{
    /**
     * Get the exam questions.
     *
     * @param int $examId
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function index($examId)
    {
        return Exam::with(['category', 'questions'])->findOrFail($examId);
    }

    /**
     * Create new exam question.
     *
     * @param QuestionRequest $request
     * @param int $examId
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(QuestionRequest $request, $examId)
    {
        $exam = Exam::findOrFail($examId, ['id']);

        $question = $exam->questions()->save(new Question($request->only(['question'])['question']));

        foreach ($request->input('option') as $option) {
            $_option = $question->options()->save(new Option(['content' => $option['content']]));

            if ($option['answer']) {
                $answers[] = $_option->getAttribute('id');
            }
        }

        if (isset($answers)) {
            $question->answers()->sync($answers);
        }

        return $this->response->created(null, $question->fresh(['options']));
    }

    /**
     * Get the exam questions id and uuid.
     *
     * @param int $examId
     *
     * @return \Illuminate\Support\Collection
     */
    public function groups($examId)
    {
        return Question::where('exam_id', $examId)
            ->whereNull('question_id')
            ->latest()
            ->get(['id', 'uuid']);
    }
}
