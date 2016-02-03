<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ApiController extends Controller
{
    /**
     * Http response status
     *
     * @var int
     */
    private $status = SymfonyResponse::HTTP_OK;

    /**
     * Http response content
     *
     * @var mixed
     */
    private $data = [];

    /**
     * Additional Http response headers
     *
     * @var array
     */
    private $headers = [];

    /**
     * Error data
     *
     * @var array
     */
    private $error = [
        'no' => 0,
        'info' => null,
    ];

    /**
     * Integrate store and update method.
     *
     * @param Model $model
     * @param Request $request
     * @param array $attributes
     * @return Model
     */
    public function storeOrUpdate(Model $model, Request $request, array $attributes = [])
    {
        $dates = $model->getDates();

        foreach ($attributes as $key => $value) {
            // 如果 key 為數字，代表值為索引，否則值為預設值
            list($k, $v) = is_int($key) ? [$value, null] : [$key, $value];

            if (in_array($k, $dates, true)) {
                $model->setAttribute($k, $request->has($k) ? Carbon::parse($request->input($k)) : $v);
            } else {
                $model->setAttribute($k, $request->has($k) ? $request->input($k) : $v);
            }
        }

        $model->exists = $model->save();

        return $model;
    }

    /**
     * Http response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function response()
    {
        return response()->json($this->data, $this->status, $this->headers);
    }

    /**
     * Http success response
     *
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    private function responseSuccess($status)
    {
        return $this->setStatus($status)->response();
    }

    /**
     * Http error response
     *
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    private function responseError($status)
    {
        $this->setData([
            'messages' => $this->data,
            'error' => $this->error,
        ]);

        return $this->setStatus($status)->response();
    }

    /**
     * Http ok response
     *
     * @link https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#200
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseOk()
    {
        return $this->responseSuccess(SymfonyResponse::HTTP_OK);
    }

    /**
     * Http created response
     *
     * @link https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#201
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseCreated()
    {
        return $this->responseSuccess(SymfonyResponse::HTTP_CREATED);
    }

    /**
     * Http unauthorized response
     *
     * @link https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#401
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseUnauthorized()
    {
        return $this->responseError(SymfonyResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * Http forbidden response
     *
     * @link https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#403
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseForbidden()
    {
        return $this->responseError(SymfonyResponse::HTTP_FORBIDDEN);
    }

    /**
     * Http not found response
     *
     * @link https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#404
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseNotFound()
    {
        return $this->responseError(SymfonyResponse::HTTP_NOT_FOUND);
    }

    /**
     * Http unprocessable entity response
     *
     * @link https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#422
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseUnprocessableEntity()
    {
        return $this->responseError(SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Http unknown error response
     *
     * @link https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#520
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseUnknownError()
    {
        return $this->responseError(520);
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array $messages
     * @return $this
     */
    public function setMessages($messages)
    {
        return $this->setData($messages);
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param array $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }
}
