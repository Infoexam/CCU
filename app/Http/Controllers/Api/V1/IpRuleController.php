<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\IpRuleRequest;
use App\Infoexam\General\Config;
use Cache;
use Illuminate\Http\Request;

class IpRuleController extends ApiController
{
    /**
     * IP 規則表
     *
     * @var \Illuminate\Support\Collection
     */
    private $ipRules;

    /**
     * IpRuleController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->ipRules = Cache::tags('config')->get('ipRules', collect());
    }

    /**
     * IpRuleController destructor
     */
    public function __destruct()
    {
        Cache::tags('config')->forever('ipRules', $this->ipRules);

        Config::updateOrCreate(['key' => 'ipRules'], [
            'key' => 'ipRules',
            'value' => serialize($this->ipRules),
        ]);
    }

    /**
     * 取得所有 IP 規則
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->setData($this->ipRules)->responseOk();
    }

    /**
     * 新增或更新 IP 規則
     *
     * @param IpRuleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(IpRuleRequest $request)
    {
        $this->ipRules->offsetSet($request->input('ip'), [
            'student' => boolval($request->input('rules.student')),
            'admin' => boolval($request->input('rules.admin')),
            'exam' => boolval($request->input('rules.exam')),
        ]);

        return $this->responseOk();
    }

    /**
     * 刪除 IP 規則
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->ipRules->forget($request->input('ip'));

        return $this->responseOk();
    }
}
