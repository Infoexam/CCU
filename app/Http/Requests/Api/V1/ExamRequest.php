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
        $rules = [
            'category_id' => 'required|integer|exists:categories,id,category,exam.category',
            'name'        => 'required|string|max:48|unique:exams,name',
            'enable'      => 'required|boolean',
            'cover'       => 'required|image',
        ];

        if ($this->isMethod('PATCH')) {
            $rules['name'] .= ','.$this->route('name').',name';

            $rules['cover'] = 'image';
        }

        return $rules;
    }
}
