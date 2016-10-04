<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class ListingApplyRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (! $this->user()->own('admin')) {
            return [];
        }

        return [
            'username' => 'required_without:department|string',
            'department' => 'required_without:username|integer|exists:categories,id,category,user.department',
            'class' => 'required|string|in:A,B',
        ];
    }
}
