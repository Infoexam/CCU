<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ExamSetRequest;
use App\Infoexam\Exam\Set;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSetController extends ApiController
{
    /**
     * ExamSetController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['index', 'show']]);
    }

    /**
     * 取得所有題庫資料
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sets = Set::with(['category'])->latest()->paginate(10, ['id', 'name', 'category_id', 'enable']);

        return $this->setData($sets)->responseOk();
    }

    /**
     * 新增題庫
     *
     * @param ExamSetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExamSetRequest $request)
    {
        $set = $this->storeOrUpdate(new Set(), $request, ['name', 'category_id', 'enable' => false]);

        if (! $set->exists) {
            return $this->responseUnknownError();
        }

        return $this->setData($set)->responseCreated();
    }

    /**
     * 取得指定題庫資料
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $set = Set::with(['category'])->find($id, ['id', 'name', 'category_id', 'enable']);

        if (is_null($set)) {
            return $this->responseNotFound();
        }

        return $this->setData($set)->responseOk();
    }

    /**
     * 更新指定題庫資料
     *
     * @param ExamSetRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExamSetRequest $request, $id)
    {
        $set = Set::find($id);

        if (is_null($set)) {
            return $this->responseNotFound();
        }

        $set = $this->storeOrUpdate($set, $request, ['name', 'category_id', 'enable']);

        if (! $set->exists) {
            return $this->responseUnknownError();
        }

        return $this->setData($set)->responseOk();
    }

    /**
     * 刪除指定題庫與其相關資料（through model event）
     * 相關資料包括：題目
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $set = Set::find($id, ['id']);

        if (is_null($set)) {
            return $this->responseNotFound();
        }

        $set->delete();

        return $this->responseOk();
    }

    /**
     * 取得所有啟用題庫的題目（用於試卷新增題目）
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allQuestions()
    {
        $set = Set::with(['questions' => function (HasMany $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'exam_set_id', 'content']);
        }])->where('enable', true)->get(['id', 'name']);

        return $this->setData($set)->responseOk();
    }
}
