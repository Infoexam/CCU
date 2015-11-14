<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class ExamSetsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (null === ($user = Auth::user())) {
            return false;
        } else if (! $user->hasRole(['admin'])) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:32',
            'category_id' => 'required|exists:categories,id,category,exam.category',
            'enable' => 'required|boolean',
        ];
    }
}
