<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class ExamPaperRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:64',
            'remark' => 'max:255',
        ];
    }
}
