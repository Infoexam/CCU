<?php

namespace App\Http\Requests;

class NewsRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'heading' => 'required|string|max:190',
            'link' => 'required|nullable|url|max:190',
            'content' => 'required|string|max:1000',
            'is_announcement' => 'required|boolean',
        ];
    }
}
