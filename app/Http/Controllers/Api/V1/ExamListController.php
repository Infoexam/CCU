<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Exam\Lists;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ExamListController extends Controller
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

        return response()->json($lists);
    }

    /**
     * 新增測驗
     *
     * @param Requests\ExamListRequest $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Requests\ExamListRequest $request)
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
        $list = Lists::with(['paper' => function (BelongsTo $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'name']);
        }, 'apply', 'subject'])->where('code', '=', $code)->firstOrFail([
            'id', 'code', 'began_at', 'duration', 'room', 'paper_id', 'apply_type_id', 'subject_id',
            'std_maximum_num', 'std_apply_num', 'std_test_num', 'allow_apply', 'started_at'
        ]);

        return response()->json($list);
    }

    /**
     * 更新指定測驗
     *
     * @param Request $request
     * @param string $code
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $code)
    {
        return $this->storeOrUpdate(Lists::where('code', '=', $code)->firstOrFail(), $request);
    }

    /**
     * Implement store or update method.
     *
     * @param Model $list
     * @param Request $request
     * @param array $attributes
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function storeOrUpdate(Model $list, Request $request, array $attributes = [])
    {
        $request->merge([
            'code' => Carbon::parse($request->input('began_at'))->format('YmdH') . $request->input('room')
        ]);

        try {
            $this->storeOrUpdate($list, $request, [
                'code', 'began_at', 'duration', 'room', 'paper_id', 'apply_type_id', 'subject_id', 'std_maximum_num'
            ]);

            return $this->ok();
        } catch (QueryException $e) {
            return response()->json(['errors' => ['create' => 'timeAlreadyUsed']], 422);
        }
    }

    /**
     * 刪除指定測驗與已報名資料
     * 如該測驗已開始，則無法刪除
     *
     * @param string $code
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy($code)
    {
        Lists::where('code', '=', $code)->whereNotNull('started_at')->firstOrFail(['id'])->delete();

        return $this->ok();
    }
}
