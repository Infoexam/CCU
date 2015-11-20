<?php

namespace App\Http\Requests;

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
