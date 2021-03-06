<?php

namespace App\Http\Requests;

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
