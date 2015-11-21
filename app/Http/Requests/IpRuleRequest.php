<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class IpRuleRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ip' => 'required',
            'ip_address' => 'ip',
            'ip_mask' => 'in:8,16,24,32',
            'rules' => 'required|array',
            'rules.student' => 'required|boolean',
            'rules.admin' => 'required|boolean',
            'rules.exam' => 'required|boolean',
        ];
    }

    protected function overrideInputs()
    {
        $ip = explode('/', $this->input('ip', ''));

        $this->merge(['ip_address' => $ip[0]]);

        if (isset($ip[1])) {
            $this->merge(['ip_mask' => $ip[1]]);
        }
    }
}
