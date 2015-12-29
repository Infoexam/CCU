<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ExamListRequest;
use App\Infoexam\Exam\Lists;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class ExamListController extends ApiController
{
    /**
     * ExamListController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['index']]);

        $this->middleware('auth:admin', ['except' => ['index']]);
    }

    /**
     * 取得所有測驗資訊，一頁 10 筆資料
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $lists = Lists::with(['apply', 'subject'])->latest('began_at')->paginate(10, [
            'code', 'began_at', 'duration', 'room', 'apply_type_id', 'subject_id',
            'std_maximum_num', 'std_apply_num', 'allow_apply',
        ]);

        return $this->setData($lists)->responseOk();
    }

    /**
     * 新增測驗
     *
     * @param ExamListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExamListRequest $request)
    {
        return $this->storeOrUpdate(new Lists(), $request);
    }

    /**
     * 查詢指定測驗
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($code)
    {
        $list = Lists::where('code', $code)->first();

        if (is_null($list)) {
            return $this->responseNotFound();
        }

        $list->load([
            'paper' => function (BelongsTo $relation) {
                $relation->getQuery()->getQuery()->select(['id', 'name']);
            },
            'apply',
            'subject'
        ]);

        return $this->setData($list)->responseOk();
    }

    /**
     * 更新指定測驗
     *
     * @param ExamListRequest $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExamListRequest $request, $code)
    {
        $list = Lists::where('code', $code)->first();

        if (is_null($list)) {
            return $this->responseNotFound();
        }

        return $this->storeOrUpdate($list, $request);
    }

    /**
     * Implement store or update method.
     *
     * @param Model $list
     * @param Request $request
     * @param array $attributes
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeOrUpdate(Model $list, Request $request, array $attributes = [])
    {
        $list = parent::storeOrUpdate($list, $request, [
            'code', 'began_at', 'duration', 'room', 'paper_id', 'apply_type_id', 'subject_id', 'std_maximum_num'
        ]);

        if (! $list->exists) {
            return $this->responseUnknownError();
        }

        $this->setData($list);

        return $request->isMethod('POST') ? $this->responseCreated() : $this->responseOk();
    }


    /**
     * 刪除指定測驗與已報名資料
     * 如該測驗已開始，則無法刪除
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($code)
    {
        $list = Lists::where('code', $code)->whereNull('started_at')->first();;

        if (is_null($list)) {
            return $this->responseNotFound();
        }

        $list->delete();

        return $this->responseOk();
    }
}
