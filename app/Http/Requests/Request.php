<?php

namespace App\Http\Requests;

use Dingo\Api\Exception\ValidationHttpException;

abstract class Request extends ValidationHttpException
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
