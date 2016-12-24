<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;
use Infoexam\Eloquent\Models\Category;

class ListingRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'began_at'      => 'required|date|after:now',
            'duration'      => 'required|integer|digits_between:1,255',
            'room'          => 'required|string|in:214,215,216,217',
            'maximum_num'   => 'required|integer|digits_between:1,255',
            'apply_type_id' => 'required|integer|exists:categories,id,category,exam.apply',
            'subject_id'    => 'required|integer|exists:categories,id,category,exam.subject',
            'applicable'    => 'required|boolean',
        ];

        $theories = Category::getCategories('exam.subject')
            ->filter(function (Category $category) {
                return ends_with($category->getAttribute('name'), 'theory');
            })
            ->pluck('id')
            ->toArray();

        if ($this->isMethod('POST') && in_array($this->input('subject_id'), $theories)) {
            $rules['auto_generate'] = 'required|boolean';

            if (! boolval($this->input('auto_generate'))) {
                $rules['paper_id'] = 'required|integer|exists:papers,id';
            } else {
                $rules['exam'] = 'required|array';
                $rules['exam.*'] = 'required|integer|exists:exams,id';
            }
        }

        return $rules;
    }
}
