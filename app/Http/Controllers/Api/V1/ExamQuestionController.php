<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamQuestionController extends Controller
{
    public function index($examId)
    {
        return Exam::with(['questions'])->findOrFail($examId);
    }
}
