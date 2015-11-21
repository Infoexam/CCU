<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ExamConfigRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'allowRoom' => 'required|array',
            'passedScore' => 'required|array',
            'passedScore.theory' => 'required|integer|min:0',
            'passedScore.technology' => 'required|integer|min:0',
            'apply' => 'required|array',
            'apply.canCancelDay' => 'required|integer|min:0',
            'apply.freeApplyGrade' => 'required|integer|in:1,2,3,4',
        ];

        foreach (array_keys($this->input('allowRoom', [])) as $key) {
            $rules["allowRoom.{$key}"] = 'required|in:214,215,216,217,109,110';
        }

        return $rules;
    }
}
