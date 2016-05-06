<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Exam;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    public function exam()
    {
        return Exam::where('enable', true)->latest()->get(['id', 'name']);
    }
    
    public function processing($id)
    {
        return Exam::with([
            'questions' => function ($query) {
                $query->whereNull('question_id')->orderBy(DB::raw('RAND()'))->limit(50);
            },
            'questions.options' => function ($query) {$query->orderBy(DB::raw('RAND()'));},
            'questions.questions',
            'questions.questions.options' => function ($query) {$query->orderBy(DB::raw('RAND()'));},
        ])->where('enable', true)->findOrFail($id);
    }
}
