<?php

namespace App\Http\Controllers\Api\V1;

use App\Infoexam\General\Config;
use Cache;
use Illuminate\Http\Request;

class WebsiteMaintenanceController extends ApiController
{
    /**
     * WebsiteMaintenanceController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * 取得網站維護設定
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        return $this->setData(Cache::tags('config')->get('websiteMaintenance', collect()))->responseOk();
    }

    /**
     * 更新網站維護設定
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $websiteMaintenance = collect([
            'student' => [
                'maintenance' => boolval($request->input('student.maintenance')),
                'message' => $request->input('student.message'),
            ],
            'exam' => [
                'maintenance' => boolval($request->input('exam.maintenance')),
                'message' => $request->input('exam.message'),
            ],
        ]);

        Cache::tags('config')->forever('websiteMaintenance', $websiteMaintenance);

        Config::updateOrCreate(['key' => 'websiteMaintenance'], [
            'key' => 'websiteMaintenance',
            'value' => serialize($websiteMaintenance),
        ]);

        return $this->responseOk();
    }
}
