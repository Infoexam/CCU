<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Exams\Question;
use App\Http\Requests;
use App\Http\Controllers\Controller;
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

    public function groups($examId)
    {
        return Question::where('exam_id', $examId)
            ->whereNull('question_id')
            ->latest()
            ->get(['id'])
            ->pluck('id');
    }
}
