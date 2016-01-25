<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;
use App\Infoexam\General\Category;
use Cache;
use Carbon\Carbon;

class ExamListRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $theories = Category::where('category', 'exam.subject')
            ->where('name', 'like', '%theory')
            ->get(['id'])
            ->implode('id', ',');

        $rooms = implode(',', Cache::tags('config')->get('exam')->get('allowRoom', []));

        if ($this->isMethod('POST')) {
            $rules = [
                'apply_type_id' => 'required|exists:categories,id,category,exam.apply',
                'subject_id' => 'required|exists:categories,id,category,exam.subject',
                'specify_paper' => "required_if:apply_type_id,{$theories}|boolean",
                'paper_id' => "required_if:specify_paper,1|required_if:apply_type_id,{$theories}|exists:exam_papers,id",
                'sets' => 'required_if:specify_paper,0|array',
                'sets.*' => 'exists:exam_sets,id',
            ];
        } else {
            $rules = [
                'code' => 'required|unique:exam_lists,code,' . $this->route('lists') . ',code',
            ];
        }

        return array_merge([
            'code' => 'required|unique:exam_lists,code',
            'began_at' => 'required|date',
            'duration' => 'required|min:1|max:255',
            'room' => "required|in:{$rooms}",
            'std_maximum_num' => 'required|min:1|max:255',
            'allow_apply' => 'boolean',
        ], $rules);
    }

    /**
     * Override or append request inputs.
     *
     * @return void
     */
    protected function overrideInputs()
    {
        $this->merge([
            'code' => Carbon::parse($this->input('began_at'))->format('YmdH') . $this->input('room'),
        ]);
    }
}
