<?php

namespace App\Http\Requests\Api\V1;

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
            'rules.student' => 'boolean',
            'rules.admin' => 'boolean',
            'rules.exam' => 'boolean',
        ];
    }

    /**
     * Override or append request inputs.
     *
     * @return void
     */
    protected function overrideInputs()
    {
        $ip = explode('/', $this->input('ip', ''));

        $this->merge(['ip_address' => $ip[0]]);

        if (isset($ip[1])) {
            $this->merge(['ip_mask' => $ip[1]]);
        }
    }
}
