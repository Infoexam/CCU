<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\CategoryRequest;
use App\Infoexam\General\Category;

class CategoryController extends ApiController
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
        return $this->setData(Category::all())->responseOk();
    }

    /**
     * 新增 Category
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $category = $this->storeOrUpdate(new Category(), $request, ['category', 'name', 'remark']);

        if (! $category->exists) {
            return $this->responseUnknownError();
        }

        return $this->setData($category)->responseCreated();
    }

    /**
     * 顯示指定 Category 資料
     *
     * @param string $category
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($category, $name = '')
    {
        $categories = explode(',', $category);

        if (1 === count($categories)) {
            $data = Category::getCategories($category, $name);
        } else {
            foreach ($categories as $category) {
                $data[$category] = Category::getCategories($category);
            }
        }

        return $this->setData($data)->responseOk();
    }

    /**
     * 更新指定 Category 資料
     *
     * @param CategoryRequest $request
     * @param string $category
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, $category, $name)
    {
        $category = Category::where('category', $category)->where('name', $name)->first();

        if (is_null($category)) {
            return $this->responseNotFound();
        }

        $category = $this->storeOrUpdate($category, $request, ['category', 'name', 'remark']);

        if (! $category->exists) {
            return $this->responseUnknownError();
        }

        return $this->setData($category)->responseOk();
    }


    /**
     * 刪除指定 Category 資料
     *
     * @param string $category
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($category, $name)
    {
        $category = Category::where('category', $category)->where('name', $name)->first();

        if (is_null($category)) {
            return $this->responseNotFound();
        }

        $category->delete();

        return $this->responseOk();
    }
}
