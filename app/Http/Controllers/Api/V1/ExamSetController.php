<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Infoexam\Exam\Set;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSetController extends Controller
{
    /**
     * 顯示所有題庫資訊
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sets = Set::with(['category'])->latest()->get(['id', 'name', 'category_id', 'enable']);

        return response()->json($sets);
    }

    /**
     * 新增題庫
     *
     * @param Requests\ExamSetsRequest $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Requests\ExamSetsRequest $request)
    {
        if (! Set::create($request->only(['name', 'category_id', 'enable']))->exists) {
            return response()->json(['errors' => ['create' => '']], 500);
        }

        return $this->ok();
    }

    /**
     * 顯示指定題庫內容
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show($id)
    {
        $set = Set::with(['questions' => function (HasMany $relation) {
            $relation->getQuery()->with(['difficulty'])->getQuery()
                ->select(['id', 'exam_set_id', 'content', 'difficulty_id', 'multiple']);
        }])->findOrFail($id, ['id', 'name']);

        return response()->json($set);
    }

    /**
     * 取得欲編輯的題庫資料
     *
     * @param int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit($id)
    {
        $set = Set::with(['category'])->findOrFail($id, ['id', 'name', 'category_id', 'enable']);

        return response()->json($set);
    }

    /**
     * 更新指定題庫資料
     *
     * @param Requests\ExamSetsRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(Requests\ExamSetsRequest $request, $id)
    {
        if (! Set::findOrFail($id, ['id', 'name', 'category_id', 'enable'])
            ->update($request->only(['name', 'category_id', 'enable']))) {
            return response()->json(['errors' => ['update' => '']], 500);
        }

        return $this->ok();
    }

    /**
     * 刪除指定題庫與其相關資料
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException|\Exception
     */
    public function destroy($id)
    {
        Set::findOrFail($id, ['id'])->delete();

        return $this->ok();
    }

    /**
     * 取得所有題目（用於試卷新增題目）
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allQuestions()
    {
        $set = Set::with(['questions' => function (HasMany $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'exam_set_id', 'content']);
        }])->where('enable', '=', true)->get(['id', 'name']);

        return response()->json($set);
    }
}
