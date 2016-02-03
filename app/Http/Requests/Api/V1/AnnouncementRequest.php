<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class AnnouncementRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'heading' => ['required', 'max:180', 'regex:/^((?!(\/|,)).)*$/', 'unique:announcements,heading'],
            'link' => 'url|min:1|max:190',
            'content' => 'required|max:1000',
            'image' => 'sometimes|array',
            'image.*' => 'sometimes|image',
        ];

        if ($this->isMethod('PATCH')) {
            $rules['heading'][3] .= ',' . $this->route('announcements') . ',heading';
        }

        return $rules;
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'heading.regex' => 'The heading field can not contain "/" or "," character.',
        ];
    }
}
