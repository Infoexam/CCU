<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Exam\Lists;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class ExamListApplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['only' => ['index']]);
    }

    public function index($listId)
    {
        $list = Lists::with(['paper' => function (BelongsTo $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'name']);
        }, 'applies' => function (HasMany $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'user_id', 'exam_list_id', 'apply_type_id', 'paid_at']);
        }, 'applies.user' => function (BelongsTo $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'username', 'name']);
        }])->findOrFail($listId, ['id', 'code', 'paper_id', 'std_maximum_num', 'std_apply_num', 'std_test_num']);

        return response()->json($list);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
