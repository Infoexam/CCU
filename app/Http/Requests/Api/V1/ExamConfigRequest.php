<?php

namespace App\Http\Requests\Api\V1;

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
        return [
            'allowRoom' => 'required|array',
            'allowRoom.*' => 'required|in:214,215,216,217,109,110',
            'passedScore' => 'required|integer|min:0',
            'apply' => 'required|array',
            'apply.canCancelDay' => 'required|integer|min:0',
            'apply.freeApplyGrade' => 'required|integer|in:1,2,3,4',
        ];
    }
}
