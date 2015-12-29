<?php

namespace App\Http\Requests\Api\V1;

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
        $category = $this->input('category');
        $name = $this->input('name');

        if ($this->isMethod('PATCH')) {
            $category = $this->route('categories');
            $name = $this->route('name');
        }

        return [
            'category' => [
                'required',
                'max:32',
                'regex:/^[\w\.]+$/',
                "unique:categories,category,{$category},category,name,{$name}",
            ],
            'name' => [
                'required',
                'max:192',
                'regex:/^[\w\.]+$/',
            ],
            'remark' => 'max:255',
        ];
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'category.regex' => 'The category field can only contain letter, number, underscore and dot.',
            'name.regex' => 'The name field can only contain letter, number, underscore and dot.',
        ];
    }
}
