<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\AnnouncementRequest;
use App\Infoexam\Website\Announcement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AnnouncementController extends ApiController
{
    /**
     * AnnouncementController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['index', 'show']]);
    }

    /**
     * 取得所有公告資料，每頁 10 筆
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10, ['heading', 'created_at', 'updated_at']);

        return $this->setData($announcements)->responseOk();
    }

    /**
     * 新增公告
     *
     * @param AnnouncementRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AnnouncementRequest $request)
    {
        return $this->storeOrUpdate(new Announcement(), $request);
    }

    /**
     * 顯示指定公告資料
     *
     * @param string $heading
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($heading)
    {
        $announcement = Announcement::with(['images'])
            ->where('heading', $heading)
            ->first(['id', 'heading', 'link', 'content', 'created_at', 'updated_at']);

        if (is_null($announcement)) {
            return $this->responseNotFound();
        }

        return $this->setData($announcement)->responseOk();
    }

    /**
     * 更新指定公告資料
     *
     * @param AnnouncementRequest $request
     * @param string $heading
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AnnouncementRequest $request, $heading)
    {
        $announcement = Announcement::where('heading', $heading)->first();

        if (is_null($announcement)) {
            return $this->responseNotFound();
        }

        return $this->storeOrUpdate($announcement, $request);
    }

    /**
     * Implement store or update method.
     *
     * @param Model $announcement
     * @param Request $request
     * @param array $attributes
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeOrUpdate(Model $announcement, Request $request, array $attributes = [])
    {
        /** @var Announcement $announcement */

        $announcement = parent::storeOrUpdate($announcement, $request, ['heading', 'link', 'content']);

        if (! $announcement->exists) {
            return $this->responseUnknownError();
        }

        if ($request->hasFile('image')) {
            $announcement->uploadImages($request->file('image'));
        }

        $this->setData($announcement);

        return $request->isMethod('POST') ? $this->responseCreated() : $this->responseOk();
    }

    /**
     * 刪除指定公告
     *
     * @param string $heading
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($heading)
    {
        $announcement = Announcement::where('heading', $heading)->first(['id']);

        if (is_null($announcement)) {
            return $this->responseNotFound();
        }

        // 避免公告標題發生碰撞
        $announcement->update(['heading' => "{$heading}|" . str_random(6)]);

        $announcement->delete();

        return $this->responseOk();
    }
}
