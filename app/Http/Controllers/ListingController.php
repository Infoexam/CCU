<?php

namespace App\Http\Controllers;

use App\Exceptions\ListingAppliedException;
use App\Exceptions\ListingConflictException;
use App\Exceptions\ListingStartedException;
use App\Http\Requests\ListingRequest;
use App\Services\ListingService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Infoexam\Eloquent\Models\Category;
use Infoexam\Eloquent\Models\Listing;

class ListingController extends Controller
{
    /**
     * @var ListingService
     */
    private $service;

    /**
     * ListingController constructor.
     *
     * @param ListingService $service
     */
    public function __construct(ListingService $service)
    {
        $this->service = $service;
    }

    /**
     * Get listing list.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        if ($request->has('apply')) {
            return Listing::with(['applyType', 'subject'])
                ->where('applicable', true)
                ->where(function (Builder $query) use ($request) {
                    $ids = Category::getCategories('exam.apply')
                        ->filter(function (Category $category) {
                            return in_array($category->getAttribute('name'), ['unity', 'makeup'], true);
                        })
                        ->pluck('id')
                        ->toArray();

                    if ($request->has('unity')) {
                        $query->whereIn('apply_type_id', $ids);
                    } else {
                        $query->whereNotIn('apply_type_id', $ids);
                    }
                })
                ->where('began_at', '>=', Carbon::now()->addDay())
                ->latest('began_at')
                ->orderBy('room')
                ->paginate(5)
                ->appends($request->only(['apply', 'unity']));
        }

        return $this->service->listing();
    }

    /**
     * Create listing.
     *
     * @param ListingRequest $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(ListingRequest $request)
    {
        try {
            $this->service->create($request->all());
        } catch (ListingConflictException $e) {
            return $this->response->error('listingConflict', 422);
        }

        return $this->response->created();
    }

    /**
     * @param string $code
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($code)
    {
        return $this->service->show($code, [
            'id', 'code', 'began_at', 'duration', 'room', 'applicable',
            'paper_id', 'apply_type_id', 'subject_id', 'maximum_num', 'applied_num',
        ]);
    }

    /**
     * Update listing.
     *
     * @param ListingRequest $request
     * @param string $code
     *
     * @return \Dingo\Api\Http\Response|void
     */
    public function update(ListingRequest $request, $code)
    {
        try {
            $this->service->update($code, $request->all());
        } catch (ListingConflictException $e) {
            return $this->response->error('listingConflict', 422);
        }

        return $this->response->noContent();
    }

    /**
     * Delete specific listing.
     *
     * @param string $code
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($code)
    {
        try {
            $this->service->destroy($code);
        } catch (ListingStartedException $e) {
            return $this->response->error('listingStarted', 422);
        } catch (ListingAppliedException $e) {
            return $this->response->error('listingApplied', 422);
        }

        return $this->response->noContent();
    }
}
