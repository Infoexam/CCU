<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ExamConfigRequest;
use App\Infoexam\General\Config;
use Cache;

class ExamConfigController extends ApiController
{
    /**
     * ExamConfigController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * 取得測驗設定
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        return $this->setData(Cache::tags('config')->get('exam', collect()))->responseOk();
    }

    /**
     * 更新測驗設定
     *
     * @param ExamConfigRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExamConfigRequest $request)
    {
        $examConfig = collect($request->only(['allowRoom', 'passedScore', 'apply']));

        Cache::tags('config')->forever('exam', $examConfig);

        Config::updateOrCreate(['key' => 'exam'], [
            'key' => 'exam',
            'value' => serialize($examConfig),
        ]);

        return $this->responseOk();
    }
}
