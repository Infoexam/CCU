<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache;
use Illuminate\Http\Request;

class IpRuleController extends Controller
{
    /**
     * IP 規則表
     *
     * @var \Illuminate\Support\Collection
     */
    protected $ipRules;

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
    }

    /**
     * 取得所有 IP 規則
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->ipRules);
    }

    /**
     * 新增或更新 IP 規則
     *
     * @param Requests\IpRuleRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Requests\IpRuleRequest $request)
    {
        $this->ipRules->offsetSet($request->input('ip'), [
            'student' => boolval($request->input('rules.student')),
            'admin' => boolval($request->input('rules.admin')),
            'exam' => boolval($request->input('rules.exam')),
        ]);

        return $this->ok();
    }

    /**
     * 刪除 IP 規則
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request)
    {
        $this->ipRules->forget($request->input('ip'));

        return $this->ok();
    }
}
