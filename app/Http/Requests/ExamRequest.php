<?php

namespace App\Http\Requests;

use Infoexam\Eloquent\Models\Category;

class ExamRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $tech = Category::getCategories('exam.category', 'tech');

        $rules = [
            'category_id' => 'required|integer|exists:categories,id,category,exam.category',
            'name'        => 'required|string|max:48|unique:exams,name',
            'enable'      => 'required|boolean',
            'cover'       => 'required|image',
            'attachment'  => "required_if:category_id,{$tech}|file",
        ];

        if ($this->isMethod('PATCH')) {
            $rules['name'] .= ','.$this->route('name').',name';

            $rules['cover'] = 'image';
        }

        return $rules;
    }
}
