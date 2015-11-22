<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Image\Upload;
use App\Infoexam\Website\Announcement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
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
        $announcements = Announcement::latest()->paginate(10, ['id', 'heading', 'created_at', 'updated_at']);

        return response()->json($announcements);
    }

    /**
     * 新增公告
     *
     * @param Requests\AnnouncementRequest $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Requests\AnnouncementRequest $request)
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
        $announcement = Announcement::with(['images'])->where('heading', '=', $heading)
            ->firstOrFail(['id', 'heading', 'link', 'content', 'created_at', 'updated_at']);

        return response()->json($announcement);
    }

    /**
     * 更新指定公告資料
     *
     * @param Requests\AnnouncementRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Requests\AnnouncementRequest $request, $id)
    {
        return $this->storeOrUpdate(Announcement::findOrFail($id), $request);
    }

    /**
     * Implement store or update method.
     *
     * @param Model $announcement
     * @param Request $request
     * @param array $attributes
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function storeOrUpdate(Model $announcement, Request $request, array $attributes = [])
    {
        if (str_contains($request->input('heading'), ['/'])) {
            return response()->json(['heading' => 'Heading can\'t contain "/".'], 422);
        }

        parent::storeOrUpdate($announcement, $request, ['heading', 'link', 'content']);

        if ($request->hasFile('image')) {
            new Upload($request->file('image'), [
                'id' => $announcement->getAttribute('id'),
                'type' => Announcement::class,
            ]);
        }

        return $this->ok();
    }

    /**
     * 刪除指定公告
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();

        return $this->ok();
    }
}
