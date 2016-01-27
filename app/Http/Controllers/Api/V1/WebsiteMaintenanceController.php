<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\WebsiteMaintenanceRequest;
use App\Infoexam\General\Config;
use Cache;

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
        $default = collect([
            'student' => ['maintenance' => false, 'message' => ''],
            'exam' => ['maintenance' => false, 'message' => ''],
        ]);

        return $this->setData(Cache::tags('config')->get('websiteMaintenance', $default))->responseOk();
    }

    /**
     * 更新網站維護設定
     *
     * @param WebsiteMaintenanceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(WebsiteMaintenanceRequest $request)
    {
        $websiteMaintenance = collect($request->all());

        Cache::tags('config')->forever('websiteMaintenance', $websiteMaintenance);

        Config::updateOrCreate(['key' => 'websiteMaintenance'], [
            'key' => 'websiteMaintenance',
            'value' => serialize($websiteMaintenance),
        ]);

        return $this->responseOk();
    }
}
