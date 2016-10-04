<?php

namespace App\Repositories;

use App\Exams\Apply;

class ApplyRepository
{
    /**
     * @var Apply
     */
    private $apply;

    /**
     * Constructor.
     *
     * @param Apply $apply
     */
    public function __construct(Apply $apply)
    {
        $this->apply = $apply;
    }

    /**
     * @return Apply
     */
    public function getApply()
    {
        return $this->apply;
    }
}
