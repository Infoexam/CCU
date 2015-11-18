<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CategoryRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category' => 'required|max:32|unique:categories,category,NULL,id,name,' . $this->input('name'),
            'name' => 'required|max:192',
            'remark' => 'max:255',
        ];
    }
}
