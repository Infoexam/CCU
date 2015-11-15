<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Exam\Paper;

class ExamPaperController extends Controller
{
    /**
     * ExamPaperController constructor.
     */
    public function __construct()
    {
        // 設定 middleware
        $this->middleware('auth:admin');
    }

    /**
     * 取得所有試卷資料
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $papers = Paper::latest()->get(['id', 'name', 'remark', 'automatic']);

        return response()->json($papers);
    }

    /**
     * 新增試卷
     *
     * @param Requests\ExamPapersRequest $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Requests\ExamPapersRequest $request)
    {
        if (! Paper::create($request->only(['name', 'remark']))->exists) {
            return response()->json(['errors' => ['create' => '']], 500);
        }

        return $this->ok();
    }

    /**
     * 取得指定試卷資料
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $paper = Paper::findOrFail($id, ['id', 'name', 'remark']);

        return response()->json($paper);
    }

    /**
     * 更新指定試卷資料
     *
     * @param Requests\ExamPapersRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Requests\ExamPapersRequest $request, $id)
    {
        if (! Paper::findOrFail($id, ['id', 'name', 'remark'])
            ->update($request->only(['name', 'remark']))) {
            return response()->json(['errors' => ['update' => '']], 500);
        }

        return $this->ok();
    }

    /**
     * 刪除指定試卷
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        Paper::findOrFail($id, ['id'])->delete();

        return $this->ok();
    }
}
