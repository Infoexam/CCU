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
            'username' => 'sometimes|required|max:32|unique:users,username',
            'password' => 'confirmed|min:6',
            'name' => 'required|max:32',
            'email' => 'required|email|max:128',
            'free' => 'required|array',
            'free.*' => 'required|integer|digits_between:0,255',
        ];

        if ($this->isMethod('PATCH')) {
            $rules['username'] .= ',' . $this->route('username') . ',username';
        } else {
            $rules['password'] .= '|required';
        }

        return $rules;
    }
}
