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
            'username' => 'required|max:32|unique:users,username',
            'password' => 'required|confirmed|min:6',
            'name' => 'required|max:32',
            'email' => 'required|email|max:255',
            'free' => 'required|array',
            'free.*' => 'required|integer',
        ];

        if ($this->isMethod('PATCH')) {
            $rules['username'] .= ',' . $this->route('username') . ',username';
        }

        return $rules;
    }
}
