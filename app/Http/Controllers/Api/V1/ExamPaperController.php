<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Requests\Api\V1\ExamPaperRequest;
use App\Infoexam\Exam\Paper;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamPaperController extends ApiController
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

        return $this->setData($papers)->responseOk();
    }

    /**
     * 新增試卷
     *
     * @param ExamPaperRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExamPaperRequest $request)
    {
        $paper = $this->storeOrUpdate(new Paper(), $request, ['name', 'remark']);

        if (! $paper->exists) {
            return $this->responseUnknownError();
        }

        return $this->setData($paper)->responseCreated();
    }

    /**
     * 取得指定試卷資料
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $paper = Paper::find($id, ['id', 'name', 'remark', 'automatic']);

        if (is_null($paper)) {
            return $this->responseNotFound();
        }

        return $this->setData($paper)->responseOk();
    }

    /**
     * 更新指定試卷資料
     *
     * @param ExamPaperRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExamPaperRequest $request, $id)
    {
        $paper = Paper::find($id);

        if (is_null($paper)) {
            return $this->responseNotFound();
        }

        $paper = $this->storeOrUpdate($paper, $request, ['name', 'remark']);

        if (! $paper->exists) {
            return $this->responseUnknownError();
        }

        return $this->setData($paper)->responseOk();
    }

    /**
     * 刪除指定試卷，如已有測驗使用該試卷，則無法刪除
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $paper = Paper::with(['_lists' => function (HasMany $relation) {
            $relation->getQuery()->getQuery()->select(['paper_id']);
        }])->find($id, ['id']);

        if (is_null($paper)) {
            return $this->responseNotFound();
        } else if ($paper->getRelation('_lists')->count()) {
            return $this->setMessages(['delete' => 'alreadyUsed'])->responseUnprocessableEntity();
        }

        $paper->delete();

        return $this->responseOk();
    }
}
