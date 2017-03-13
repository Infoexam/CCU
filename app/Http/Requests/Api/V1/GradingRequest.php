<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class GradingRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'   => 'required|integer|exists:applies,id',
            'score' => 'required|numeric|between:0,100',
        ];
    }
}
