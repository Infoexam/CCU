<?php

namespace App\Http\Requests;

class ExamImageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'collection' => 'sometimes|required|string|in:default,cover',
            'image'      => 'array',
            'image.*'    => 'required|image',
        ];
    }
}
