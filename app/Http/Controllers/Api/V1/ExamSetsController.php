<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Exam\Set;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class ExamSetsController extends Controller
{
    /**
     * 顯示所有題庫資訊
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sets = Set::with(['category'])->latest()->paginate(10, ['id', 'name', 'category_id', 'enable']);

        return response()->json($sets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\ExamSetsRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\ExamSetsRequest $request)
    {
        Set::create($request->only(['name', 'category_id', 'enable']));

        return $this->ok();
    }

    /**
     * 顯示指定題庫內容
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show($id)
    {
        $set = Set::with(['questions' => function (HasMany $relation) {
            $relation->getQuery()->with(['difficulty'])->getQuery()
                ->select(['id', 'exam_set_id', 'content', 'difficulty_id', 'multiple']);
        }])->findOrFail($id, ['id', 'name']);

        return response()->json($set);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $set = Set::with(['category'])->findOrFail($id, ['id', 'name', 'category_id', 'enable']);

        return response()->json($set);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Requests\ExamSetsRequest|Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\ExamSetsRequest $request, $id)
    {
        Set::findOrFail($id, ['id', 'name', 'category_id', 'enable'])
            ->update($request->only(['name', 'category_id', 'enable']));

        return $this->ok();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
