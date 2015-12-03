<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Exam\Lists;
use App\Infoexam\Exam\Result;
use Illuminate\Http\Request;

class ExamListResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $listCode
     * @return \Illuminate\Http\Response
     */
    public function index($listCode)
    {
        $list = Lists::with(['applies', 'applies.result'])->where('code', '=', $listCode)->firstOrFail();

        return response()->json($list);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $listCode
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $listCode, $id)
    {
        Result::find($id)->update($request->only(['score']));

        return $this->ok();
    }
}
