<?php

namespace App\Http\Controllers\Api\V1;

use App\Infoexam\Exam\Lists;
use App\Infoexam\Exam\Result;
use Illuminate\Http\Request;

class ExamListResultController extends ApiController
{
    /**
     * 查詢測驗結果
     *
     * @param string $listCode
     * @return \Illuminate\Http\Response
     */
    public function index($listCode)
    {
        $list = Lists::with(['applies', 'applies.result'])->where('code', $listCode)->first();

        if (is_null($list)) {
            return $this->responseNotFound();
        }

        return $this->setData($list)->responseOk();
    }

    /**
     * 更新測驗成績
     *
     * @param \Illuminate\Http\Request $request
     * @param string $code
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code, $id)
    {
        $result = Result::find($id);

        if (is_null($request)) {
            return $this->responseNotFound();
        }

        $result->update($request->only(['score']));

        return $this->setData($request)->responseOk();
    }
}
