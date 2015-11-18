<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\General\Category;

class CategoryController extends Controller
{
    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * 取得所有 Category 資料
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Category::all());
    }

    /**
     * 新增 Category
     *
     * @param Requests\CategoryRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Requests\CategoryRequest $request)
    {
        $this->storeOrUpdate(new Category(), $request, ['category', 'name', 'remark']);

        return $this->ok();
    }

    /**
     * 顯示指定 Category 資料
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return response()->json(Category::findOrFail($id));
    }

    /**
     * 更新指定 Category 資料
     *
     * @param Requests\CategoryRequest $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Requests\CategoryRequest $request, $id)
    {
        $this->storeOrUpdate(Category::findOrFail($id), $request, ['category', 'name', 'remark']);

        return $this->ok();
    }

    /**
     * 刪除指定 Category 資料
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return $this->ok();
    }
}
