<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Exams\Option;
use App\Exams\Question;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\QuestionRequest;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamQuestionController extends Controller
{
    /**
     * Get the exam questions.
     *
     * @param int $examId
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index($examId)
    {
        return Exam::with([
            'category',
            'questions' => function (HasMany $query) {
                $query->getQuery()->whereNUll('question_id');
            },
            'questions.difficulty',
            'questions.options',
        ])->findOrFail($examId);
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

        $question = $exam->questions()->save(new Question($request->input(['question'])));

        foreach ($request->input('option') as $option) {
            $question->options()->save(new Option($option));
        }

        return $this->response->created();
    }

    /**
     * Get exam question.
     *
     * @param int $examId
     * @param string $uuid
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show($examId, $uuid)
    {
        $question = Question::with(['difficulty', 'options', 'answers'])
            ->where('exam_id', $examId)
            ->where('uuid', $uuid)
            ->firstOrFail();

        return $question;
    }

    /**
     * Get the exam questions id and uuid.
     *
     * @param int $examId
     *
     * @return \Dingo\Api\Http\Response
     */
    public function groups($examId)
    {
        return Question::where('exam_id', $examId)
            ->whereNull('question_id')
            ->latest()
            ->get(['id', 'uuid']);
    }
}
