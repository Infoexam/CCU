<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Infoexam\Exam\Set;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSetController extends Controller
{
    /**
     * ExamSetController constructor.
     */
    public function __construct()
    {
        // 設定 middleware
        $this->middleware('auth:admin', ['except' => ['index', 'show']]);
    }

    /**
     * 取得所有題庫資料
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
     * @param Requests\ExamSetRequest $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Requests\ExamSetRequest $request)
    {
        if (! Set::create($request->only(['name', 'category_id', 'enable']))->exists) {
            return response()->json(['errors' => ['create' => '']], 500);
        }

        return $this->ok();
    }

    /**
     * 取得指定題庫資料
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $set = Set::with(['category'])->findOrFail($id, ['id', 'name', 'category_id', 'enable']);

        return response()->json($set);
    }

    /**
     * 更新指定題庫資料
     *
     * @param Requests\ExamSetRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Requests\ExamSetRequest $request, $id)
    {
        if (! Set::findOrFail($id, ['id', 'name', 'category_id', 'enable'])
            ->update($request->only(['name', 'category_id', 'enable']))) {
            return response()->json(['errors' => ['update' => '']], 500);
        }

        return $this->ok();
    }

    /**
     * 刪除指定題庫與其相關資料（through model event）
     * 相關資料包括：題目
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        Set::findOrFail($id, ['id'])->delete();

        return $this->ok();
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
        }])->where('enable', '=', true)->get(['id', 'name']);

        return response()->json($set);
    }
}
