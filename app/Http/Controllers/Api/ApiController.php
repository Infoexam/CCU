<?php

namespace App\Http\Controolers\Api;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ApiController extends Controller
{
    /**
     * HTTP status code
     *
     * @var int
     */
    private $status = SymfonyResponse::HTTP_OK;

    /**
     * Response data
     *
     * @var array
     */
    private $data = [];

    /**
     * Error message
     *
     * @var array
     */
    private $errors = [];

    /**
     * Additional headers
     *
     * @var array
     */
    private $headers = [];

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function response()
    {
        return response()->json($this->getData(), $this->getStatus(), $this->getHeaders());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseWithErrors()
    {
        return $this->setData([
            'code' => $this->getStatus(),
            'errors' => $this->getErrors(),
        ])->response();
    }

    /**
     * HTTP not found response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseNotFound()
    {
        return $this->setStatus(SymfonyResponse::HTTP_NOT_FOUND)->responseWithErrors();
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
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
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function appendErrors($message)
    {
        $this->errors[] = $message;

        return $this;
    }
}
