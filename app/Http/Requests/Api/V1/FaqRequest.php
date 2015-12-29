<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class FaqRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question' => 'required|max:255',
            'answer' => 'required|max:255',
        ];
    }
}
