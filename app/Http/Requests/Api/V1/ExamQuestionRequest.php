<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class ExamQuestionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'question'               => 'required|array',
            'question.uuid'          => 'required|string|max:36|unique:questions,uuid',
            'question.content'       => 'required|string|max:5000',
            'question.multiple'      => 'required|boolean',
            'question.difficulty_id' => 'required|integer|exists:categories,id,category,exam.difficulty',
            'question.explanation'   => 'string|max:5000',
            'question.question_id'   => 'integer|exists:questions,id',
            'option'                 => 'required|array',
            'option.*.content'       => 'required|string|max:1000',
            'option.*.answer'        => 'required|boolean',
        ];

        if ($this->isMethod('PATCH')) {
            $rules['question.uuid'] .= ','.$this->route('uuid').',uuid';
            $rules['option.*.id'] = 'sometimes|required|integer|exists:options,id';
        }

        return $rules;
    }
}
