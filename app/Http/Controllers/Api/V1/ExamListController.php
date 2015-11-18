<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Exam\Lists;
use Illuminate\Http\Request;

class ExamListController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth', ['only' => ['index']]);

        //$this->middleware('auth:admin', ['except' => ['index']]);
    }

    public function index()
    {
        $lists = Lists::with(['apply', 'subject'])->latest('began_at')->paginate(10, [
            'code', 'began_at', 'duration', 'room', 'apply_type_id', 'subject_id',
            'std_maximum_num', 'std_apply_num', 'allow_apply',
        ]);

        return response()->json($lists);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $list = Lists::with(['apply', 'subject', 'paper'])->findOrFail($id);

        return response()->json($list);
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
