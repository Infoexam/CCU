<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\General\Config;
use Cache;

class ExamConfigController extends Controller
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
        $examConfig = Cache::tags('config')->get('exam', collect());

        return response()->json($examConfig);
    }

    /**
     * 更新測驗設定
     *
     * @param Requests\ExamConfigRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Requests\ExamConfigRequest $request)
    {
        $examConfig = collect($request->only(['allowRoom', 'passedScore', 'apply']));

        Cache::tags('config')->forever('exam', $examConfig);

        Config::updateOrCreate(['key' => 'exam'], [
            'key' => 'exam',
            'value' => serialize($examConfig),
        ]);

        return $this->ok();
    }
}
