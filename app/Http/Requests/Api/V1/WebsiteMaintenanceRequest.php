<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Request;

class WebsiteMaintenanceRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'student' => 'required|array',
            'student.maintenance' => 'required|boolean',
            'student.message' => 'string',
            'exam' => 'required|array',
            'exam.maintenance' => 'required|boolean',
            'exam.message' => 'string',
        ];
    }
}
