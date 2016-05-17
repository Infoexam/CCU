<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class ExamRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required|integer|exists:categories,id,category,exam.category',
            'name'        => 'required|string|max:64|unique:exams,name',
            'enable'      => 'required|boolean',
            'cover'       => 'required|image',
        ];
    }
}
