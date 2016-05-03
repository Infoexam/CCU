<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        return Exam::paginate();
    }
}
