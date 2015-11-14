<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Exam\Paper;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExamPapersController extends Controller
{
    /**
     * 顯示所有試卷
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $papers = Paper::latest()->get(['id', 'name', 'remark', 'automatic']);

        return response()->json($papers);
    }

    /**
     * 創建試卷
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
     * 顯示指定試卷題目
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show($id)
    {
        $paper = Paper::with(['questions' => function (BelongsToMany $relation) {
            $relation->getQuery()->with(['difficulty'])->getQuery()
                ->select(['exam_questions.id', 'content', 'difficulty_id', 'multiple']);
        }])->findOrFail($id, ['id', 'name']);

        return response()->json($paper);
    }

    /**
     * 取得欲編輯的試卷資料
     *
     * @param int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit($id)
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
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException|\Exception
     */
    public function destroy($id)
    {
        Paper::findOrFail($id, ['id'])->delete();

        return $this->ok();
    }
}
