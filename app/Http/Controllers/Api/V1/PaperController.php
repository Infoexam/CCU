<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Paper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PaperRequest;
use Illuminate\Http\Request;

class PaperController extends Controller
{
    /**
     * Get the paper list.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        if ($request->has('listing')) {
            return Paper::withCount(['questions'])
                ->where('automatic', false)
                ->get(['id', 'name']);
        }

        return Paper::withCount(['questions'])
            ->orderBy('automatic')
            ->latest()
            ->paginate(null, ['name', 'remark']);
    }

    /**
     * Create a new paper.
     *
     * @param PaperRequest $request
     *
     * @return Paper
     */
    public function store(PaperRequest $request)
    {
        return Paper::create($request->only(['name', 'remark']));
    }

    /**
     * Get the paper data.
     *
     * @param string $name
     *
     * @return Paper
     */
    public function show($name)
    {
        return Paper::where('name', $name)->firstOrFail(['name', 'remark']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PaperRequest $request
     * @param string $name
     *
     * @return Paper
     */
    public function update(PaperRequest $request, $name)
    {
        $paper = Paper::where('name', $name)->firstOrFail(['id', 'name', 'remark']);

        if (! $paper->update($request->only(['name', 'remark']))) {
            $this->response->errorInternal();
        }

        return $paper;
    }

    /**
     * Delete the paper and it's related data.
     *
     * @param string $name
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($name)
    {
        $paper = Paper::withCount(['listings'])->where('name', $name)->firstOrFail(['id', 'name']);

        if ($paper->getAttribute('listings_count') > 0) {
            $this->response->error('nonEmptyListing', 422);
        } elseif (! $paper->delete()) {
            $this->response->errorInternal();
        }

        return $this->response->noContent();
    }
}
