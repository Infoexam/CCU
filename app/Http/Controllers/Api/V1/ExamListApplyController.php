<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Infoexam\Exam\Apply;
use App\Infoexam\Exam\ApplyService;
use App\Infoexam\Exam\Lists;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class ExamListApplyController extends ApiController
{
    /**
     * ExamListApplyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin', ['only' => ['index']]);

        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * 取得測驗報名資料
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($code)
    {
        $list = Lists::with(['applies' => function (HasMany $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'user_id', 'exam_list_id', 'apply_type_id', 'paid_at']);
        }, 'applies.user' => function (BelongsTo $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'username', 'name']);
        }])->where('code', $code)->first(['id', 'code']);

        if (is_null($list)) {
            return $this->responseNotFound();
        }

        return $this->setData($list->getRelation('applies'))->responseOk();
    }

    /**
     * 測驗報名
     *
     * @param Request $request
     * @param ApplyService $applyService
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, ApplyService $applyService)
    {
        if (! $applyService->create($request)->success()) {
            return $this->setData($applyService->getErrors())->responseUnprocessableEntity();
        }

        return $this->responseCreated();
    }

    /**
     * 查看報名資料
     *
     * @param string $code
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code, $id)
    {
        $apply = Apply::find($id);

        if (is_null($apply)) {
            return $this->responseNotFound();
        }

        return $this->setData($apply)->responseOk();
    }

    /**
     * 刪除報名資料
     *
     * @param Request $request
     * @param string $code
     * @param int $id
     * @param ApplyService $applyService
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $code, $id, ApplyService $applyService)
    {
        if ($applyService->destroy($request, $code, $id)->success()) {
            return $this->setData($applyService->getErrors())->responseUnprocessableEntity();
        }

        return $this->responseOk();
    }
}
