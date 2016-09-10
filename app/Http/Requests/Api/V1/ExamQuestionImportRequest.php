<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class ExamQuestionImportRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|mimes:xls,xlsx,csv',
        ];
    }
}
