<?php

namespace App\Repositories;

use App\Services\PaperService;
use Illuminate\Support\Collection;
use Infoexam\Eloquent\Models\Category;
use Infoexam\Eloquent\Models\Listing;

class ListingRepository
{
    /**
     * @var Listing
     */
    private $listing;

    /**
     * @var PaperService
     */
    private $paperService;

    /**
     * ListingRepository constructor.
     *
     * @param Listing $listing
     * @param PaperService $paperService
     */
    public function __construct(Listing $listing, PaperService $paperService)
    {
        $this->listing = $listing;
        $this->paperService = $paperService;
    }

    /**
     * Fill the listing model with an array of attributes.
     *
     * @param Collection $input
     *
     * @return $this
     */
    public function fill(Collection $input)
    {
        $this->getListing()
            ->fill($input->only(['began_at', 'duration', 'room', 'applicable', 'apply_type_id', 'subject_id', 'maximum_num'])->toArray());

        if ($this->isTheory()) {
            $this->fillPaper($input);
        }

        return $this;
    }

    /**
     * Fill the listing model with an array of attributes.
     *
     * @param Collection $input
     *
     * @return $this
     */
    protected function fillPaper(Collection $input)
    {
        $paper_id = boolval($input->get('auto_generate'))
            ? $this->paperService->createFromExams($input->get('exam'))->getKey()
            : $input->get('paper_id');

        $this->getListing()
            ->setAttribute('paper_id', $paper_id);

        return $this;
    }

    /**
     * Check the listing is theory.
     *
     * @return bool
     */
    public function isTheory()
    {
        static $theories = null;

        if (is_null($theories)) {
            $theories = Category::getCategories('exam.subject')
                ->filter(function (Category $category) {
                    return ends_with($category->getAttribute('name'), 'theory');
                })
                ->pluck('id')
                ->toArray();
        }

        return in_array($this->getListing()->getAttribute('subject_id'), $theories);
    }

    /**
     * @return Listing
     */
    public function getListing()
    {
        return $this->listing;
    }

    /**
     * @param Listing $listing
     *
     * @return $this
     */
    public function setListing(Listing $listing)
    {
        $this->listing = $listing;

        return $this;
    }
}
