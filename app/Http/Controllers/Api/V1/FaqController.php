<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Requests\Api\V1\FaqRequest;
use App\Infoexam\Website\Faq;

class FaqController extends ApiController
{
    /**
     * FaqController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['index']]);
    }

    /**
     * 取得所有 faq 資料
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->setData(Faq::all(['id', 'question', 'answer']))->responseOk();
    }


    /**
     * 新增 faq
     *
     * @param FaqRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FaqRequest $request)
    {
        $faq = $this->storeOrUpdate(new Faq(), $request, ['question', 'answer']);

        if (! $faq->exists) {
            return $this->responseUnknownError();
        }

        return $this->setData($faq)->responseCreated();
    }


    /**
     * 取得指定 faq 資料
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $faq = Faq::find($id, ['id', 'question', 'answer']);

        if (is_null($faq)) {
            return $this->responseNotFound();
        }

        return $this->setData($faq)->responseOk();
    }

    /**
     * 更新指定 faq 資料
     *
     * @param FaqRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(FaqRequest $request, $id)
    {
        $faq = Faq::find($id);

        if (is_null($faq)) {
            return $this->responseNotFound();
        }

        $faq = $this->storeOrUpdate($faq, $request, ['question', 'answer']);

        if (! $faq->exists) {
            return $this->responseUnknownError();
        }

        return $this->setData($faq)->responseOk();
    }

    /**
     * 刪除指定 faq
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $faq = Faq::find($id, ['id']);

        if (is_null($faq)) {
            return $this->responseNotFound();
        }

        $faq->delete();

        return $this->responseOk();
    }
}
