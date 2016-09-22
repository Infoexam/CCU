<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ListingAppliedException;
use App\Exceptions\ListingConflictException;
use App\Exceptions\ListingStartedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListingRequest;
use App\Services\ListingService;

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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
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
