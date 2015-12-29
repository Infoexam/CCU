<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class ExamPaperQuestionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'questions' => 'required|array',
            'questions.*' => 'required|integer|exists:exam_questions,id',
        ];
    }
}
