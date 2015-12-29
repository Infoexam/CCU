<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class ExamSetQuestionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question' => 'required|array',
            'question.content' => 'required|max:1000|string',
            'question.image' => 'sometimes|array',
            'question.image.*' => 'sometimes|image',
            'option' => 'required|array',
            'option.*.content' => 'required|max:1000|string',
            'option.*.image' => 'sometimes|array',
            'option.*.image.*' => 'sometimes|image',
            'explanation' => 'max:1000|string',
            'difficulty_id' => 'required|exists:categories,id,category,exam.difficulty',
            'multiple' => 'required|boolean',
            'answer' => 'required|array',
            'answer.*' => 'required|integer',
        ];
    }
}
