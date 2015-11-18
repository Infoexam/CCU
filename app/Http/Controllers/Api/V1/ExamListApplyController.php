<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Exam\Apply;
use App\Infoexam\Exam\Lists;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class ExamListApplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['only' => ['index']]);

        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($listCode)
    {
        $list = Lists::with(['applies' => function (HasMany $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'user_id', 'exam_list_id', 'apply_type_id', 'paid_at']);
        }, 'applies.user' => function (BelongsTo $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'username', 'name']);
        }])->where('code', '=', $listCode)->firstOrFail(['id', 'code']);

        return response()->json($list->getRelation('applies'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show($listCode, $id)
    {
        //
    }

    public function update(Request $request, $listCode, $id)
    {
        //
    }

    public function destroy($listCode, $id)
    {
        //
    }
}
