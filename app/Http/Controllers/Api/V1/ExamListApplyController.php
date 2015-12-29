<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Infoexam\Exam\Apply;
use App\Infoexam\Exam\ApplyService;
use App\Infoexam\Exam\Lists;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExamListApplyController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['only' => ['index']]);

        $this->middleware('auth', ['except' => 'index']);
    }

    /** @todo Code refactoring */
    public function index($listCode)
    {
        $list = Lists::with(['applies' => function (HasMany $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'user_id', 'exam_list_id', 'apply_type_id', 'paid_at']);
        }, 'applies.user' => function (BelongsTo $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'username', 'name']);
        }])->where('code', '=', $listCode)->firstOrFail(['id', 'code']);

        return response()->json($list->getRelation('applies'));
    }

    /** @todo Code refactoring */
    public function store(Request $request, ApplyService $applyService)
    {
        if (! $applyService->create($request)->success()) {
            return $this->setErrors($applyService->getErrors())->responseWithErrors();
        }

        return $this->responseCreated();
    }

    /** @todo Code refactoring */
    public function show($listCode, $id)
    {
        $apply = Apply::find($id);

        return response()->json($apply);
    }

    /** @todo Code refactoring */
    public function update(Request $request, $listCode, $id)
    {
        //
    }

    /** @todo Code refactoring */
    public function destroy($code, $id, ApplyService $applyService)
    {
        if ($applyService->destroy($id)->success()) {
            return $this->setErrors($applyService->getErrors())->responseWithErrors();
        }

        return $this->responseOk();
    }
}
