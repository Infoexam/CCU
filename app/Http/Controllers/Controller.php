<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * return 200 http response
     *
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ok(array $headers = [])
    {
        return response('', 200, $headers);
    }

    /**
     * Integrate store and update method.
     *
     * @param Model $model
     * @param Request $request
     * @param array $attributes
     * @return bool
     */
    public function storeOrUpdate(Model $model, Request $request, array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            // 如果 key 為數字，代表值為索引，否則值為預設值
            list($k, $v) = is_int($key) ? [$value, null] : [$key, $value];

            $model->setAttribute($k, $request->input($k, $v));
        }

        return $model->save();
    }
}
