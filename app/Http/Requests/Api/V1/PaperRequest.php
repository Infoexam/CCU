<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class PaperRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'   => 'required|string|max:16|unique:papers,name',
            'remark' => 'string|max:190',
        ];

        if ($this->isMethod('PATCH')) {
            $rules['name'] .= ','.$this->route('name').',name';
        }

        return $rules;
    }
}
