<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class PaperQuestionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question'   => 'array',
            'question.*' => 'required|integer|exists:questions,id',
        ];
    }
}
