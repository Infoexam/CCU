<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ExamSetRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:32',
            'category_id' => 'required|exists:categories,id,category,exam.category',
            'enable' => 'required|boolean',
        ];
    }
}
