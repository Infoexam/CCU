<?php

namespace App\Http\Requests;

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
            'heading' => 'required|max:190|unique:announcements,heading',
            'link' => 'url|mix:1|max:190',
            'content' => 'required|max:1000',
            'image' => 'array',
        ];

        if ($this->isMethod('PATCH')) {
            $rules['heading'] .= ',' . $this->route('announcements');
        }

        if (is_array($this->input('image'))) {
            foreach (array_keys($this->input('image', [])) as $key) {
                $rules["image.{$key}"] = 'image';
            }
        }

        return $rules;
    }
}
