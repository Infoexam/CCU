<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ExamRequest;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        return Exam::with(['category'])->latest()->paginate(6);
    }

    public function store(ExamRequest $request)
    {
        $exam = Exam::create($request->only(['category_id', 'name', 'enable']));

        return $this->response->created(null, $exam);
    }
}
