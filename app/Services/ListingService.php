<?php

namespace App\Services;

use App\Exceptions\ListingAppliedException;
use App\Exceptions\ListingConflictException;
use App\Exceptions\ListingStartedException;
use App\Repositories\ListingRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Infoexam\Eloquent\Models\Listing;

class ListingService
{
    /**
     * @var ListingRepository
     */
    private $repository;

    /**
     * ListingService constructor.
     *
     * @param ListingRepository $repository
     */
    public function __construct(ListingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get listings list.
     *
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listing($columns = ['*'])
    {
        return $this->repository
            ->getListing()
            ->with(['applyType', 'subject', 'paper'])
            ->latest('began_at')
            ->orderBy('room')
            ->paginate(null, $columns);
    }

    /**
     * Create listing.
     *
     * @param array $input
     *
     * @return \App\Exams\Listing
     *
     * @throws ListingConflictException
     */
    public function create(array $input)
    {
        if ($this->isConflict($input)) {
            throw new ListingConflictException;
        }

        $input = new Collection($input);

        $this->repository
            ->fill($input)
            ->getListing()
            ->save();

        return $this->repository->getListing();
    }

    /**
     * @param string $code
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($code, $columns = ['*'])
    {
        return $this->repository
            ->getListing()
            ->with(['applyType', 'subject'])
            ->where('code', $code)
            ->firstOrFail($columns);
    }

    /**
     * Update listing.
     *
     * @param string $code
     * @param array $input
     *
     * @return \App\Exams\Listing
     *
     * @throws ListingConflictException
     */
    public function update($code, $input)
    {
        if ($this->isConflict($input, $code)) {
            throw new ListingConflictException;
        }

        $this->repository
            ->setListing(
                $this->repository
                ->getListing()
                ->where('code', $code)
                ->firstOrFail()
            )
            ->getListing()
            ->update(
                (new Collection($input))->only(['began_at', 'duration', 'room', 'maximum_num', 'apply_type_id', 'paper_id', 'applicable'])->toArray()
            );

        return $this->repository->getListing();
    }

    /**
     * @param $code
     *
     * @return bool|null
     *
     * @throws ListingStartedException
     * @throws ListingAppliedException
     * @throws \Exception
     */
    public function destroy($code)
    {
        $listing = $this->repository
            ->getListing()
            ->withCount(['applies'])
            ->where('code', $code)
            ->firstOrFail();

        if (! is_null($listing->getAttribute('started_at'))) {
            throw new ListingStartedException;
        } elseif ($listing->getAttribute('applies_count') > 0) {
            throw new ListingAppliedException;
        }

        return $listing->delete();
    }

    /**
     * Check a listing is applicable.
     *
     * @param Listing|null $listing
     *
     * @return bool
     */
    public function applicable(Listing $listing = null)
    {
        if (is_null($listing)) {
            $listing = $this->repository->getListing();
        }

        // @todo Prevent ended_at
        if (! $listing->exists) {
            return false;
        } elseif (! Auth::user()->own('admin')) {
            if ($listing->getAttribute('applied_num') >= $listing->getAttribute('maximum_num')) {
                return false;
            } elseif (1 > Carbon::now()->diffInDays($listing->getAttribute('began_at'), false)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check the listing is conflict or not.
     *
     * @param array $input
     * @param null|string $except
     *
     * @return bool
     */
    protected function isConflict(array $input, $except = null)
    {
        $builder = $this->repository
            ->getListing()
            ->where(function (Builder $query) use ($input) {
                $beganAt = Carbon::parse($input['began_at']);
                $endedAt = $beganAt->copy()->addMinutes($input['duration']);

                $query
                    ->where(function (Builder $query) use ($beganAt) {
                        $query->where('began_at', '<=', $beganAt)->where('ended_at', '>=', $beganAt);
                    })
                    ->orWhere(function (Builder $query) use ($endedAt) {
                        $query->where('began_at', '<=', $endedAt)->where('ended_at', '>=', $endedAt);
                    })
                    ->orWhere(function (Builder $query) use ($beganAt, $endedAt) {
                        $query->where('began_at', '>=', $beganAt)->where('ended_at', '<=', $endedAt);
                    })
                    ->orWhere(function (Builder $query) use ($beganAt, $endedAt) {
                        $query->where('began_at', '<=', $beganAt)->where('ended_at', '>=', $endedAt);
                    });
            })
            ->where('room', $input['room']);

        if (! is_null($except)) {
            $builder = $builder->where('code', '!=', $except);
        }

        return $builder->exists();
    }

    public function getListing()
    {
        return $this->repository->getListing();
    }
}
