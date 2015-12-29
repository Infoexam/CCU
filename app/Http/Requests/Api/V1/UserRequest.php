<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class UserRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'username' => 'max:32|unique:users,username',
            'password' => 'confirmed|min:6',
            'name' => 'max:32',
            'email' => 'email|max:255',
            'free' => 'array',
        ];

        if ($this->isMethod('POST')) {
            foreach ($rules as &$rule) {
                $rule .= '|required';
            }
        }

        return $rules;
    }
}
