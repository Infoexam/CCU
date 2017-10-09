<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use Hashids;
use Infoexam\Eloquent\Models\News;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        return News::latest()->paginate(5);
    }

    public function store(NewsRequest $request)
    {
        return News::create($request->only(['heading', 'link', 'content']));
    }

    public function show($heading)
    {
        return News::findOrFail($this->transformParameters($heading));
    }

    public function update(NewsRequest $request, $heading)
    {
        $news = News::findOrFail($this->transformParameters($heading));

        $news->update($request->only(['heading', 'link', 'content']));

        return $news->fresh();
    }

    public function destroy($heading)
    {
        News::findOrFail($this->transformParameters($heading))->delete();

        return $this->response->noContent();
    }

    /**
     * Transform the cs parameter to original number.
     *
     * @param string $heading
     *
     * @return int
     */
    protected function transformParameters($heading)
    {
        $ids = Hashids::connection('news')->decode(array_last(explode('-', $heading)));

        if (empty($ids)) {
            $this->response->errorNotFound();
        }

        return $ids[0];
    }
}
