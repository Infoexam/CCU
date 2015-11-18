<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Infoexam\General\Category;

class ExamListRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $theories = Category::where('category', '=', 'exam.subject')
            ->where('name', 'like', '%theory')
            ->get(['id'])
            ->implode('id', ',');

        return [
            'began_at' => 'required|date',
            'duration' => 'required|min:1|max:255',
            'room' => 'required',
            'paper_id' => "required_if:apply_type_id,{$theories}|exists:exam_papers,id",
            'apply_type_id' => 'required|exists:categories,id,category,exam.apply',
            'subject_id' => 'required|exists:categories,id,category,exam.subject',
            'std_maximum_num' => 'required|max:255',
        ];
    }
}
