<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Exam\Paper;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamPaperController extends Controller
{
    /**
     * ExamPaperController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * 取得所有試卷資料
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $papers = Paper::orderBy('automatic')->latest()->paginate(10, ['id', 'name', 'remark', 'automatic']);

        return response()->json($papers);
    }

    /**
     * 新增試卷
     *
     * @param Requests\ExamPaperRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Requests\ExamPaperRequest $request)
    {
        $this->storeOrUpdate(new Paper(), $request, ['name', 'remark']);

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
        $paper = Paper::findOrFail($id, ['id', 'name', 'remark', 'automatic']);

        return response()->json($paper);
    }

    /**
     * 更新指定試卷資料
     *
     * @param Requests\ExamPaperRequest $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Requests\ExamPaperRequest $request, $id)
    {
        $this->storeOrUpdate(Paper::findOrFail($id), $request, ['name', 'remark']);

        return $this->ok();
    }

    /**
     * 刪除指定試卷，如已有測驗使用該試卷，則無法刪除
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $paper = Paper::with(['_lists' => function (HasMany $relation) {
            $relation->getQuery()->getQuery()->select(['paper_id']);
        }])->findOrFail($id, ['id']);

        if ($paper->getRelation('_lists')->count()) {
            return response()->json(['errors' => ['delete' => 'alreadyUsed']], 422);
        }

        return $this->ok();
    }
}
