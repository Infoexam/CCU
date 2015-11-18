<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Website\Faq;

class FaqController extends Controller
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
        $faqs = Faq::all(['id', 'question', 'answer']);

        return response()->json($faqs);
    }


    /**
     * 新增 faq
     *
     * @param Requests\FaqRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Requests\FaqRequest $request)
    {
        $this->storeOrUpdate(new Faq(), $request, ['question', 'answer']);

        return $this->ok();
    }


    /**
     * 取得指定 faq 資料
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $faq = Faq::findOrFail($id, ['id', 'question', 'answer']);

        return response()->json($faq);
    }

    /**
     * 更新指定 faq 資料
     *
     * @param Requests\FaqRequest $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Requests\FaqRequest $request, $id)
    {
        $this->storeOrUpdate(Faq::findOrFail($id), $request, ['question', 'answer']);

        return $this->ok();
    }

    /**
     * 刪除指定 faq
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        Faq::findOrFail($id, ['id'])->delete();

        return $this->ok();
    }
}
