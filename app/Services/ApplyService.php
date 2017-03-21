<?php

namespace App\Services;

use App\Exceptions\ApplyInconsistentTransformException;
use App\Exceptions\ApplyUnapplicableException;
use App\Exceptions\ApplyUncancelableException;
use App\Exceptions\ApplyUntransformableException;
use App\Notifications\ListingApplied;
use App\Repositories\ApplyRepository;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infoexam\Eloquent\Models\Apply;
use Infoexam\Eloquent\Models\Category;
use Infoexam\Eloquent\Models\Listing;
use Infoexam\Eloquent\Models\User;
use Notification;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ApplyService
{
    /**
     * @var ApplyRepository
     */
    private $repository;

    /**
     * @var ListingService
     */
    private $listingService;

    /**
     * Constructor.
     *
     * @param ApplyRepository $repository
     * @param ListingService $listingService
     */
    public function __construct(ApplyRepository $repository, ListingService $listingService)
    {
        $this->repository = $repository;
        $this->listingService = $listingService;
    }

    /**
     * @param $code
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getList($code, $columns = ['*'])
    {
        return $this->getListing()
            ->with(['applies' => function (HasMany $query) use ($columns) {
                $query->select($columns);
            }, 'applies.user' => function (BelongsTo $query) {
                $query->select(['id', 'username', 'name']);
            }])
            ->where('code', $code)
            ->firstOrFail(['id', 'code']);
    }

    public function apply($code, User $user = null, $unity = false)
    {
        $listing = $this->getListing()->where('code', $code)->firstOrFail();

        if (! $this->listingService->applicable($listing)) {
            throw new ApplyUnapplicableException;
        }

        if (is_null($user)) {
            $user = Auth::user();

            if ($this->applied($user, $listing)) {
                return false;
            }
        }

        if ($this->passed($user, $listing)) {
            return false;
        }

        if ($this->validSubject($user, $listing)) {
            return false;
        }

        if (! $this->miscellaneous($user, $listing)) {
            return false;
        }

        $apply = $listing->applies()->save(new Apply([
            'user_id' => $user->getKey(),
            'type' => $user->own('admin') ? ($unity ? 'U' : 'A') : 'S',
            'paid_at' => ($this->isFree($user, $listing) || $unity) ? Carbon::now() : null,
        ]));

        $listing->increment('applied_num');

        Notification::send([$user], new ListingApplied($apply));

        return true;
    }

    protected function applied(User $user, Listing $listing)
    {
        return DB::table('applies')
            ->where('user_id', $user->getKey())
            ->where('listing_id', $listing->getKey())
            ->exists();
    }

    protected function passed(User $user, Listing $listing)
    {
        $type = $this->type($listing);

        $user->load(['certificates' => function (HasMany $query) use ($type) {
            $query->where('category_id', Category::getCategories('exam.category', $type));
        }]);

        $score = $user->getRelation('certificates')->first()->getAttribute('score');

        if (is_null($score)) {
            return false;
        }

        return $score >= 70 || 0 > $score;
    }

    protected function validSubject(User $user, Listing $listing)
    {
        if (! $user->relationLoaded('department')) {
            $user->load(['department']);
        }

        if (! $listing->relationLoaded('subject')) {
            $listing->load(['subject']);
        }

        if ('4104' == $user->getRelation('department')->getAttribute('name')) {
            $subject = $listing->getRelation('subject')->getAttribute('name');

            if ('app' != explode('-', $subject, 2)[0]) {
                return false;
            }
        }

        return true;
    }

    protected function miscellaneous(User $user, Listing $listing)
    {
        if (! Auth::user()->own('admin')) {
            if (! $listing->relationLoaded('applyType')) {
                $listing->load(['applyType']);
            }

            if (! $user->relationLoaded('grade')) {
                $user->load(['grade']);
            }

            $applyType = $listing->getRelation('applyType')->getAttribute('name');

            if (in_array($applyType, ['unity', 'makeup'])) {
                return false;
            }

            if ('senior-only' == $applyType && 'senior' != $user->getRelation('grade')) {
                return false;
            }

            $time = $listing->getAttribute('began_at');

            // @todo 拆分學術科
            $listings = DB::table('listings')->whereBetween('began_at', [$time->startOfWeek(), $time->copy()->endOfWeek()])->get(['id'])->pluck('id')->all();

            if (($key = array_search($listing->getKey(), $listings)) !== false) {
                unset($listings[$key]);
            }

            if (DB::table('applies')->where('user_id', $user->getKey())->whereIn('listing_id', $listings)->exists()) {
                return false;
            }
        }

        if (DB::table('applies')->where('user_id', $user->getKey())->where('listing_id', $listing->getKey())->exists()) {
            return false;
        }

        return true;
    }

    protected function isFree(User $user, Listing $listing)
    {
        $type = $this->type($listing);

        $user->load(['certificates' => function (HasMany $query) use ($type) {
            $query->where('category_id', Category::getCategories('exam.category', $type));
        }]);

        return $user->getRelation('certificates')->first()->getAttribute('free') > 0;
    }

    protected function type(Listing $listing)
    {
        if (! $listing->relationLoaded('subject')) {
            $listing->load(['subject']);
        }

        $subject = $listing->getRelation('subject')->getAttribute('name');

        return explode('-', $subject, 2)[1];
    }

    public function transform($fromId, $toCode)
    {
        $apply = $this->repository->getApply()->with(['listing'])->findOrFail($fromId);

        $this->cancelable($apply->getRelation('listing'));

        $listing = $this->getListing()->with(['applyType'])->where('code', $toCode)->firstOrFail();

        if (Auth::user()->own('admin')) {
            if (! is_null($listing->getAttribute('started_at'))) {
                throw new ApplyUntransformableException;
            }
        } else {
            if (1 >= Carbon::now()->diffInDays($listing->getAttribute('began_at'), false) ||
                $listing->getAttribute('applied_num') >= $listing->getAttribute('maximum_num')
            ) {
                throw new ApplyUntransformableException;
            } elseif ($listing->getAttribute('subject_id') !== $apply->getRelation('listing')->getAttribute('subject_id') ||
                ! in_array($listing->getRelation('applyType')->getAttribute('name'), ['unity', 'makeup'], true)
            ) {
                throw new ApplyInconsistentTransformException;
            }
        }

        $listing->increment('applied_num');

        $apply->getRelation('listing')->decrement('applied_num');

        return $apply->update([
            'listing_id' => $listing->getKey(),
        ]);
    }

    /**
     * Delete an apply.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy($id)
    {
        $apply = $this->repository
            ->getApply()
            ->with(['listing'])
            ->findOrFail($id);

        $this->authenticate($apply)
            ->cancelable($apply->getRelation('listing'));

        if (! $apply->delete()) {
            return false;
        }

        $apply->getRelation('listing')->decrement('applied_num');

        return true;
    }

    /**
     * Authenticate the user has permission to perform the action.
     *
     * @param Apply $apply
     *
     * @return $this
     */
    protected function authenticate(Apply $apply)
    {
        if (! Auth::user()->own('admin')) {
            if ($apply->getAttribute('user_id') !== Auth::user()->getKey()) {
                throw new AccessDeniedHttpException;
            }
        }

        return $this;
    }

    /**
     * Check an apply of listing is cancelable.
     *
     * @param Listing $listing
     *
     * @return $this
     */
    protected function cancelable(Listing $listing)
    {
        if (Auth::user()->own('admin')) {
            if (! is_null($listing->getAttribute('started_at'))) {
                throw new ApplyUncancelableException;
            }
        } elseif (1 >= Carbon::now()->diffInDays($listing->getAttribute('began_at'), false)) {
            throw new ApplyUncancelableException;
        }

        return $this;
    }

    public function getListing()
    {
        return $this->listingService->getListing();
    }
}
